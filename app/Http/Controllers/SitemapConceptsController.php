<?php

namespace App\Http\Controllers;

use App\Models\ConceptTranslation;
use App\Support\Locales;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class SitemapConceptsController extends Controller
{
    private const int MIN_PER_FILE = 100;
    private const int CACHE_TTL_SECONDS = 1800;

    public function __invoke(string $locale, int $chunk): Response
    {
        if (! Locales::isSupported($locale) || $chunk < 1) {
            abort(404);
        }

        $per = max(self::MIN_PER_FILE, (int) config('seo.sitemap.concepts_per_file', 2000));
        $boundaries = $this->chunkBoundaries($locale, $per);
        $window = $boundaries[$chunk] ?? null;
        if (! is_array($window)) {
            abort(404);
        }

        $xml = Cache::remember(
            $this->sitemapChunkCacheKey($locale, $chunk, $per, $window),
            now()->addSeconds(self::CACHE_TTL_SECONDS),
            function () use ($locale, $window): string {
                [$startId, $endId] = $window;

                $urls = ConceptTranslation::query()
                    ->forPublishedLocale($locale)
                    ->whereBetween('id', [$startId, $endId])
                    ->orderBy('id')
                    ->get(['slug'])
                    ->map(fn (ConceptTranslation $t): string => route('glossary.concept', [
                        'locale' => $locale,
                        'slug' => $t->slug,
                    ], absolute: true))
                    ->values();

                return view('sitemaps.urlset', ['urls' => Collection::make($urls)])->render();
            },
        );

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    /**
     * @return array<int, array{0:int,1:int}>
     */
    private function chunkBoundaries(string $locale, int $per): array
    {
        return Cache::remember(
            "sitemap:concepts:boundaries:v1:{$locale}:{$per}",
            now()->addSeconds(self::CACHE_TTL_SECONDS),
            static function () use ($locale, $per): array {
                $boundaries = [];
                $chunkIndex = 1;

                ConceptTranslation::query()
                    ->forPublishedLocale($locale)
                    ->orderBy('id')
                    ->select(['id'])
                    ->chunkById($per, function (Collection $rows) use (&$boundaries, &$chunkIndex): void {
                        $first = $rows->first();
                        $last = $rows->last();
                        if (! $first || ! $last) {
                            return;
                        }

                        $boundaries[$chunkIndex] = [(int) $first->id, (int) $last->id];
                        $chunkIndex++;
                    }, 'id', 'id');

                return $boundaries;
            },
        );
    }

    /**
     * @param  array{0:int,1:int}  $window
     */
    private function sitemapChunkCacheKey(string $locale, int $chunk, int $per, array $window): string
    {
        return implode(':', [
            'sitemap',
            'concepts',
            'xml',
            'v1',
            $locale,
            (string) $chunk,
            (string) $per,
            (string) $window[0],
            (string) $window[1],
        ]);
    }
}
