<?php

namespace App\Support;

use App\Models\ConceptTranslation;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * Glossary search: Meilisearch via Scout when `SCOUT_DRIVER=meilisearch`, otherwise Eloquent lexical (Phase 5).
 */
final class GlossarySearch
{
    /**
     * @return Builder<ConceptTranslation>
     */
    public static function query(string $localeCode, string $needle): Builder
    {
        $needle = trim($needle);
        $languageId = Language::activeIdForCode($localeCode);

        if ($needle === '' || $languageId === null) {
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
        if (GlossaryScoutQuery::usesMeilisearch()) {
            try {
                return self::dropdownMeilisearch($localeCode, trim($needle), $limit);
            } catch (\Throwable $e) {
                Log::warning('glossary.meilisearch.dropdown_failed', [
                    'message' => $e->getMessage(),
                    'locale' => $localeCode,
                ]);
            }
        }

        return self::dropdownLexical($localeCode, trim($needle), $limit);
    }

    public static function paginate(string $localeCode, string $needle, int $perPage = 15): LengthAwarePaginator
    {
        if (GlossaryScoutQuery::usesMeilisearch()) {
            try {
                return self::paginateMeilisearch($localeCode, trim($needle), $perPage);
            } catch (\Throwable $e) {
                Log::warning('glossary.meilisearch.paginate_failed', [
                    'message' => $e->getMessage(),
                    'locale' => $localeCode,
                ]);
            }
        }

        return self::paginateLexical($localeCode, trim($needle), $perPage);
    }

    public static function count(string $localeCode, string $needle): int
    {
        if (GlossaryScoutQuery::usesMeilisearch() && trim($needle) !== '') {
            try {
                return (int) ConceptTranslation::search(trim($needle))
                    ->where('language_code', $localeCode)
                    ->where('is_published', true)
                    ->paginate(1)
                    ->total();
            } catch (\Throwable $e) {
                Log::warning('glossary.meilisearch.count_failed', ['message' => $e->getMessage()]);
            }
        }

        return (int) self::query($localeCode, $needle)->count();
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
            ->with([
                'language',
                'concept.domains.translations' => fn ($q) => $q->where('language_id', Language::activeIdForCode($localeCode)),
            ])
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
        $languageId = Language::activeIdForCode($localeCode);

        return self::ordered(self::query($localeCode, $needle), $needle)
            ->with([
                'language',
                'concept.domains.translations' => fn ($q) => $q->where('language_id', $languageId),
            ])
            ->paginate($perPage)
            ->withQueryString();
    }

    private static function emptyPaginator(int $perPage): LengthAwarePaginator
    {
        return new LengthAwarePaginator([], 0, $perPage, 1, [
            'path' => request()->url(),
            'pageName' => 'page',
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

        return $query
            ->orderByRaw('CASE WHEN term LIKE ? THEN 0 ELSE 1 END', [$prefix])
            ->orderBy('term');
    }
}
