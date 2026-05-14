<?php

namespace App\Http\Controllers;

use App\Models\DomainTranslation;
use App\Models\Language;
use App\Support\Locales;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

final class SitemapDomainsController extends Controller
{
    public function __invoke(string $locale): Response
    {
        if (! Locales::isSupported($locale)) {
            abort(404);
        }

        $languageId = Language::activeIdForCode($locale);
        if ($languageId === null) {
            abort(404);
        }

        $urls = DomainTranslation::query()
            ->where('language_id', $languageId)
            ->whereHas('domain', fn ($q) => $q->where('is_active', true))
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('id')
            ->get(['slug'])
            ->map(fn (DomainTranslation $t): string => route('domains.show', [
                'locale' => $locale,
                'slug' => $t->slug,
            ], absolute: true));

        return response()
            ->view('sitemaps.urlset', ['urls' => Collection::make($urls)])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
