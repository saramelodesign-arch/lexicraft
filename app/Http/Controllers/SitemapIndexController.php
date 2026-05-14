<?php

namespace App\Http\Controllers;

use App\Models\ConceptTranslation;
use App\Support\Locales;
use Illuminate\Http\Response;

final class SitemapIndexController extends Controller
{
    public function __invoke(): Response
    {
        $per = max(100, (int) config('seo.sitemap.concepts_per_file', 2000));
        $locales = Locales::codes();

        $sitemaps = [];
        foreach ($locales as $locale) {
            $sitemaps[] = route('sitemaps.static', ['locale' => $locale], absolute: true);

            $count = ConceptTranslation::query()->forPublishedLocale($locale)->count();
            $chunks = max(1, (int) ceil($count / $per));
            for ($i = 1; $i <= $chunks; $i++) {
                $sitemaps[] = route('sitemaps.concepts', ['locale' => $locale, 'chunk' => $i], absolute: true);
            }

            $sitemaps[] = route('sitemaps.domains', ['locale' => $locale], absolute: true);
        }

        return response()
            ->view('sitemaps.index', ['sitemaps' => $sitemaps])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
