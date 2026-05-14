<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

final class SearchResultsController extends Controller
{
    public function __invoke(Request $request, string $locale): View
    {
        $q = mb_substr(trim($request->string('q')->toString()), 0, 200);

        $pageTitle = $q !== ''
            ? __('Search: :q', ['q' => $q])
            : __('Search');

        $metaDescription = $q !== ''
            ? __('LexiCraft Glossary results for ":q" in this language. Paginated definitions with domain context.', ['q' => $q])
            : __('Search published terms, definitions, and domains in LexiCraft Glossary.');

        $canonical = $q !== ''
            ? route('search', ['locale' => $locale, 'q' => $q], absolute: true)
            : route('search', ['locale' => $locale], absolute: true);

        return view('pages.search-results', [
            'locale' => $locale,
            'q' => $q,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonical' => $canonical,
            'ogUrl' => $canonical,
            'robotsMeta' => 'noindex,follow',
        ]);
    }
}
