<?php

namespace App\Http\Controllers;

use App\Support\Locales;
use App\Support\Seo\StructuredData;
use Illuminate\Contracts\View\View;

final class LearningHubController extends Controller
{
    public function __invoke(string $locale): View
    {
        $pageTitle = __('learning.hub_title');
        $metaDescription = __('learning.hub_meta_description');
        $canonical = route('learning.index', ['locale' => $locale], absolute: true);

        $alternates = [];
        foreach (Locales::codes() as $code) {
            $alternates[$code] = route('learning.index', ['locale' => $code], absolute: true);
        }
        $xDefaultUrl = $alternates[Locales::fallback()] ?? (count($alternates) > 0 ? reset($alternates) : $canonical);

        $jsonLdBlocks = [
            StructuredData::organization(),
            StructuredData::webSite($locale),
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $pageTitle,
                'url' => $canonical,
                'description' => $metaDescription,
                'inLanguage' => str_replace('_', '-', $locale),
            ],
        ];

        return view('pages.learning.index', [
            'locale' => $locale,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonical' => $canonical,
            'alternates' => $alternates,
            'xDefaultUrl' => $xDefaultUrl,
            'jsonLdBlocks' => $jsonLdBlocks,
        ]);
    }
}
