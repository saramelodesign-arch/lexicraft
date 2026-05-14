<?php

namespace App\Http\Controllers;

use App\Models\ConceptTranslation;
use App\Support\Locales;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

final class SitemapConceptsController extends Controller
{
    public function __invoke(string $locale, int $chunk): Response
    {
        if (! Locales::isSupported($locale) || $chunk < 1) {
            abort(404);
        }

        $per = max(100, (int) config('seo.sitemap.concepts_per_file', 2000));
        $total = ConceptTranslation::query()->forPublishedLocale($locale)->count();
        $maxChunk = max(1, (int) ceil($total / $per));
        if ($chunk > $maxChunk) {
            abort(404);
        }

        $urls = ConceptTranslation::query()
            ->forPublishedLocale($locale)
            ->orderBy('id')
            ->skip(($chunk - 1) * $per)
            ->take($per)
            ->get(['slug'])
            ->map(fn (ConceptTranslation $t): string => route('glossary.concept', [
                'locale' => $locale,
                'slug' => $t->slug,
            ], absolute: true));

        return response()
            ->view('sitemaps.urlset', ['urls' => Collection::make($urls)])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
