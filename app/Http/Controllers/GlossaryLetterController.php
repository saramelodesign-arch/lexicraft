<?php

namespace App\Http\Controllers;

use App\Support\Locales;
use App\Support\Seo\StructuredData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class GlossaryLetterController extends Controller
{
    public function __invoke(Request $request, string $locale, string $letter): View
    {
        $letterUpper = strtoupper($letter);
        $localeMeta = Locales::supported()[$locale] ?? null;
        $languageLabel = is_array($localeMeta) ? ($localeMeta['native'] ?? $localeMeta['name'] ?? $locale) : $locale;

        $pageTitle = __('Glossary — letter :letter', ['letter' => $letterUpper]);
        $metaDescription = __('LexiCraft Glossary: terms starting with :letter in :language.', [
            'letter' => $letterUpper,
            'language' => $languageLabel,
        ]);

        $canonical = route('glossary.letter', ['locale' => $locale, 'letter' => strtolower($letterUpper)], absolute: true);

        $alternates = [];
        foreach (Locales::codes() as $code) {
            $alternates[$code] = route('glossary.letter', ['locale' => $code, 'letter' => strtolower($letterUpper)], absolute: true);
        }

        $xDefaultUrl = $alternates[Locales::fallback()] ?? (count($alternates) > 0 ? reset($alternates) : $canonical);

        $page = max(1, (int) $request->query('page', 1));
        $robotsPagination = $page > 1 ? 'noindex,follow' : null;

        $breadcrumbRows = [
            ['name' => __('Home'), 'url' => route('home', ['locale' => $locale], absolute: true)],
            ['name' => $pageTitle, 'url' => $canonical],
        ];

        $breadcrumb = StructuredData::breadcrumbList($breadcrumbRows);

        $collectionPage = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $pageTitle,
            'description' => $metaDescription,
            'url' => $canonical,
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => config('app.name'),
                'url' => route('home', ['locale' => $locale], absolute: true),
            ],
        ];

        return view('pages.glossary-letter', [
            'letter' => $letterUpper,
            'locale' => $locale,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonical' => $canonical,
            'ogUrl' => $canonical,
            'alternates' => $alternates,
            'xDefaultUrl' => $xDefaultUrl,
            'jsonLdBlocks' => [$collectionPage, $breadcrumb],
            'breadcrumbs' => $breadcrumbRows,
            'robotsMeta' => $robotsPagination,
        ]);
    }
}
