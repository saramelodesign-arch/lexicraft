<?php

namespace App\Http\Controllers;

use App\Support\Locales;
use App\Support\Search\GlossarySearchFilters;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SearchResultsController extends Controller
{
    public function __invoke(Request $request, string $locale): View
    {
        $q = mb_substr(trim($request->string('q')->toString()), 0, 200);
        $filters = GlossarySearchFilters::fromQuery($request->query(), $locale, $request->user() !== null);

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
            $query = array_filter(array_merge(
                ['q' => $q !== '' ? $q : null],
                $filters->toQuery(),
            ));

            $alternates[$code] = $q !== ''
                ? route('search', array_merge(['locale' => $code], $query), absolute: true)
                : route('search', array_merge(['locale' => $code], $query), absolute: true);
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
