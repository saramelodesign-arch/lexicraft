<?php

namespace App\Http\Controllers;

use App\Support\Locales;
use App\Support\Seo\StructuredData;
use Illuminate\Contracts\View\View;

final class LearningHubController extends Controller
{
    public function __invoke(string $locale): View
    {
        $pageTitle = __('Terminology learning');
        $metaDescription = __(
            'LexiCraft Glossary learning hub: flashcards, quizzes, and semantic practice mapped to multilingual concepts, domains, and relations.',
        );
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
