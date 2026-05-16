<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Language;
use App\Support\Editorial\WorkflowStatus;
use App\Support\Locales;
use App\Support\Seo\StructuredData;
use Illuminate\Contracts\View\View;

final class DomainIndexController extends Controller
{
    public function __invoke(string $locale): View
    {
        $languageId = Language::activeIdForCode($locale);

        if ($languageId === null) {
            abort(404);
        }

        $roots = Domain::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with([
                'translations' => fn ($q) => $q->where('language_id', $languageId),
                'children' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->with([
                    'translations' => fn ($t) => $t->where('language_id', $languageId),
                    'children' => fn ($c) => $c->where('is_active', true)->orderBy('sort_order')->with([
                        'translations' => fn ($t2) => $t2->where('language_id', $languageId),
                    ]),
                ]),
            ])
            ->withCount([
                'concepts as terms_count' => function ($q) use ($languageId): void {
                    $q->where('concepts.status', WorkflowStatus::PUBLISHED)
                        ->whereHas('translations', fn ($t) => $t->where('language_id', $languageId)->where('status', WorkflowStatus::PUBLISHED));
                },
            ])
            ->orderBy('sort_order')
            ->get();

        $pageTitle = __('search.industrial_domains');
        $metaDescription = __('search.domains_meta_description');
        $canonical = route('domains.index', ['locale' => $locale], absolute: true);

        $alternates = [];
        foreach (Locales::codes() as $code) {
            $alternates[$code] = route('domains.index', ['locale' => $code], absolute: true);
        }

        $xDefaultUrl = $alternates[Locales::fallback()] ?? (count($alternates) > 0 ? reset($alternates) : $canonical);

        $structuredData = [
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

        $breadcrumbRows = [
            ['name' => __('ui.home'), 'url' => route('home', ['locale' => $locale], absolute: true)],
            ['name' => $pageTitle, 'url' => $canonical],
        ];

        return view('pages.domains-index', [
            'locale' => $locale,
            'roots' => $roots,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonical' => $canonical,
            'ogUrl' => $canonical,
            'alternates' => $alternates,
            'xDefaultUrl' => $xDefaultUrl,
            'structuredData' => $structuredData,
            'jsonLdBlocks' => [
                StructuredData::breadcrumbList($breadcrumbRows),
            ],
            'breadcrumbs' => $breadcrumbRows,
        ]);
    }
}
