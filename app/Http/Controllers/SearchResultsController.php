<?php

namespace App\Http\Controllers;

use App\Support\Locales;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SearchResultsController extends Controller
{
    public function __invoke(Request $request, string $locale): View
    {
        $q = mb_substr(trim($request->string('q')->toString()), 0, 200);

        $pageTitle = $q !== ''
            ? __('seo.search_title_with_query', ['q' => $q])
            : __('seo.search_title');

        $metaDescription = $q !== ''
            ? __('seo.search_meta_with_query', ['q' => $q])
            : __('seo.search_meta_default');

        $canonical = $q !== ''
            ? route('search', ['locale' => $locale, 'q' => $q], absolute: true)
            : route('search', ['locale' => $locale], absolute: true);

        $alternates = [];
        foreach (Locales::codes() as $code) {
            $alternates[$code] = $q !== ''
                ? route('search', ['locale' => $code, 'q' => $q], absolute: true)
                : route('search', ['locale' => $code], absolute: true);
        }

        $xDefaultUrl = $alternates[Locales::fallback()] ?? $canonical;

        return view('pages.search-results', [
            'locale' => $locale,
            'q' => $q,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonical' => $canonical,
            'ogUrl' => $canonical,
            'robotsMeta' => 'noindex,follow',
            'alternates' => $alternates,
            'xDefaultUrl' => $xDefaultUrl,
        ]);
    }
}
