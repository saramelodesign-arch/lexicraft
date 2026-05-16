<?php

namespace App\Support;

use App\Models\ConceptTranslation;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Glossary search: Meilisearch via Scout when `SCOUT_DRIVER=meilisearch`, otherwise Eloquent lexical (Phase 5).
 */
final class GlossarySearch
{
    private const int MIN_QUERY_LENGTH = 2;

    private const int MAX_QUERY_LENGTH = 200;
    private const int MAX_DROPDOWN_LIMIT = 12;
    private const int MAX_PER_PAGE = 50;
    private const int MAX_FALLBACK_PAGE = 100;
    private const int COUNT_SCAN_CAP = 5000;

    /**
     * @return Builder<ConceptTranslation>
     */
    public static function query(string $localeCode, string $needle): Builder
    {
        $needle = self::normalizeNeedle($needle);
        $languageId = Language::activeIdForCode($localeCode);

        if (! self::isQueryableNeedle($needle) || $languageId === null || ! self::allowLookup('query')) {
            return ConceptTranslation::query()->whereRaw('1 = 0');
        }

        return ConceptTranslation::query()
            ->forPublishedLocale($localeCode)
            ->searchLexical($needle, $languageId);
    }

    /**
     * @return Collection<int, ConceptTranslation>
     */
    public static function dropdown(string $localeCode, string $needle, int $limit = 8): Collection
    {
        $needle = self::normalizeNeedle($needle);
        $limit = self::normalizeDropdownLimit($limit);
        if (! self::isQueryableNeedle($needle) || ! self::allowLookup('dropdown')) {
            return collect();
        }

        if (GlossaryScoutQuery::usesMeilisearch()) {
            try {
                return self::dropdownMeilisearch($localeCode, $needle, $limit);
            } catch (\Throwable $e) {
                Log::warning('glossary.meilisearch.dropdown_failed', [
                    'message' => $e->getMessage(),
                    'locale' => $localeCode,
                ]);
            }
        }

        return self::dropdownLexical($localeCode, $needle, $limit);
    }

    public static function paginate(string $localeCode, string $needle, int $perPage = 15): LengthAwarePaginator
    {
        $needle = self::normalizeNeedle($needle);
        $perPage = self::normalizePerPage($perPage);
        if (! self::isQueryableNeedle($needle) || ! self::allowLookup('paginate')) {
            return self::emptyPaginator($perPage);
        }

        if (GlossaryScoutQuery::usesMeilisearch()) {
            try {
                return self::paginateMeilisearch($localeCode, $needle, $perPage);
            } catch (\Throwable $e) {
                Log::warning('glossary.meilisearch.paginate_failed', [
                    'message' => $e->getMessage(),
                    'locale' => $localeCode,
                ]);
            }
        }

        return self::paginateLexical($localeCode, $needle, $perPage);
    }

    public static function count(string $localeCode, string $needle): int
    {
        $needle = self::normalizeNeedle($needle);
        if (! self::isQueryableNeedle($needle) || ! self::allowLookup('count')) {
            return 0;
        }

        if (GlossaryScoutQuery::usesMeilisearch()) {
            try {
                return (int) ConceptTranslation::search($needle)
                    ->where('language_code', $localeCode)
                    ->where('is_published', true)
                    ->paginate(1)
                    ->total();
            } catch (\Throwable $e) {
                Log::warning('glossary.meilisearch.count_failed', ['message' => $e->getMessage()]);
            }
        }

        $capped = self::query($localeCode, $needle)
            ->select('concept_translations.id')
            ->limit(self::COUNT_SCAN_CAP + 1);

        $count = (int) DB::query()->fromSub($capped, 'capped_glossary_search')->count();

        return min($count, self::COUNT_SCAN_CAP);
    }

    /**
     * @return Collection<int, ConceptTranslation>
     */
    private static function dropdownMeilisearch(string $localeCode, string $needle, int $limit): Collection
    {
        if ($needle === '' || ! Locales::isSupported($localeCode)) {
            return collect();
        }

        $languageId = Language::activeIdForCode($localeCode);

        return ConceptTranslation::search($needle)
            ->where('language_code', $localeCode)
            ->where('is_published', true)
            ->query(fn ($q) => $q->with([
                'language',
                'concept.domains.translations' => fn ($dt) => $languageId !== null
                    ? $dt->where('language_id', $languageId)
                    : $dt,
            ]))
            ->take($limit)
            ->options(GlossaryScoutQuery::highlightOptions())
            ->get();
    }

    /**
     * @return Collection<int, ConceptTranslation>
     */
    private static function dropdownLexical(string $localeCode, string $needle, int $limit): Collection
    {
        $languageId = Language::activeIdForCode($localeCode);

        return self::ordered(self::query($localeCode, $needle), $needle)
            ->with(self::fallbackRelations($languageId))
            ->limit($limit)
            ->get();
    }

    private static function paginateMeilisearch(string $localeCode, string $needle, int $perPage): LengthAwarePaginator
    {
        if ($needle === '' || ! Locales::isSupported($localeCode)) {
            return self::emptyPaginator($perPage);
        }

        $languageId = Language::activeIdForCode($localeCode);

        $scoutPaginator = ConceptTranslation::search($needle)
            ->where('language_code', $localeCode)
            ->where('is_published', true)
            ->query(fn ($q) => $q->with([
                'language',
                'concept.domains.translations' => fn ($dt) => $languageId !== null
                    ? $dt->where('language_id', $languageId)
                    : $dt,
            ]))
            ->options(GlossaryScoutQuery::highlightOptions())
            ->paginate($perPage);

        return new LengthAwarePaginator(
            $scoutPaginator->items(),
            $scoutPaginator->total(),
            $scoutPaginator->perPage(),
            $scoutPaginator->currentPage(),
            [
                'path' => $scoutPaginator->path(),
                'pageName' => $scoutPaginator->getPageName(),
                'query' => array_filter(['q' => $needle]),
            ],
        );
    }

    private static function paginateLexical(string $localeCode, string $needle, int $perPage): LengthAwarePaginator
    {
        $page = max(1, (int) request()->integer('page', 1));
        if ($page > self::MAX_FALLBACK_PAGE) {
            return self::emptyPaginator($perPage, $page);
        }

        $languageId = Language::activeIdForCode($localeCode);

        return self::ordered(self::query($localeCode, $needle), $needle)
            ->with(self::fallbackRelations($languageId))
            ->paginate($perPage)
            ->withQueryString();
    }

    private static function emptyPaginator(int $perPage, int $page = 1): LengthAwarePaginator
    {
        return new LengthAwarePaginator([], 0, $perPage, $page, [
            'path' => request()->url(),
            'pageName' => 'page',
            'query' => array_filter(['q' => request()->query('q')]),
        ]);
    }

    /**
     * @param  Builder<ConceptTranslation>  $query
     * @return Builder<ConceptTranslation>
     */
    private static function ordered(Builder $query, string $needle): Builder
    {
        $needle = trim($needle);
        $escaped = addcslashes($needle, '%_\\');
        $prefix = $escaped !== '' ? $escaped.'%' : '%';
        $operator = $query->getConnection()->getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';

        return $query
            ->orderByRaw("CASE WHEN term {$operator} ? THEN 0 ELSE 1 END", [$prefix])
            ->orderBy('term');
    }

    private static function normalizeNeedle(string $needle): string
    {
        return mb_substr(trim($needle), 0, self::MAX_QUERY_LENGTH);
    }

    private static function isQueryableNeedle(string $needle): bool
    {
        return mb_strlen($needle) >= self::MIN_QUERY_LENGTH;
    }

    private static function normalizeDropdownLimit(int $limit): int
    {
        return max(1, min($limit, self::MAX_DROPDOWN_LIMIT));
    }

    private static function normalizePerPage(int $perPage): int
    {
        return max(1, min($perPage, self::MAX_PER_PAGE));
    }

    /**
     * @return array<string, mixed>
     */
    private static function fallbackRelations(?int $languageId): array
    {
        return [
            'language:id,code,name,native_name,is_active',
            'concept:id,status',
            'concept.domains:id,parent_id,slug,icon,sort_order,is_active',
            'concept.domains.translations' => fn ($q) => $q
                ->select(['id', 'domain_id', 'language_id', 'slug', 'name', 'description'])
                ->when($languageId !== null, fn ($dt) => $dt->where('language_id', $languageId)),
        ];
    }

    private static function allowLookup(string $bucket): bool
    {
        $request = request();
        $identity = $request->user()?->id !== null
            ? 'user:'.$request->user()->id
            : 'ip:'.$request->ip();

        $key = 'search:lookup:'.$bucket.':'.$identity;
        if (RateLimiter::tooManyAttempts($key, 240)) {
            return false;
        }

        RateLimiter::hit($key, 60);

        return true;
    }
}
