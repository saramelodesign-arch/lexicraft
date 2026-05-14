<?php

namespace App\Http\Controllers;

use App\Support\Locales;
use App\Support\Seo\StructuredData;
use Illuminate\Contracts\View\View;

final class HomeController extends Controller
{
    public function __invoke(string $locale): View
    {
        $pageTitle = __('Industrial glossary');
        $metaDescription = __(
            'Industrial terminology for footwear, leather goods, belts, manufacturing, CAD/CAM, and production processes. Search definitions, domains, and multilingual concepts.',
        );
        $canonical = route('home', ['locale' => $locale], absolute: true);

        $alternates = [];
        foreach (Locales::codes() as $code) {
            $alternates[$code] = route('home', ['locale' => $code], absolute: true);
        }

        $xDefaultUrl = $alternates[Locales::fallback()] ?? (count($alternates) > 0 ? reset($alternates) : $canonical);

        $jsonLdBlocks = [
            StructuredData::organization(),
            StructuredData::webSite($locale),
            StructuredData::definedTermSetHome($locale, $metaDescription),
        ];

        return view('pages.home', [
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
