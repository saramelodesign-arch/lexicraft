<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\DomainTranslation;
use App\Models\Language;
use App\Support\Locales;
use App\Support\Seo\StructuredData;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class DomainShowController extends Controller
{
    public function __invoke(string $locale, string $slug): View
    {
        $slug = mb_strtolower($slug, 'UTF-8');

        $languageId = Language::activeIdForCode($locale);

        if ($languageId === null) {
            abort(404);
        }

        /** @var DomainTranslation $translation */
        $translation = DomainTranslation::query()
            ->where('slug', $slug)
            ->where('language_id', $languageId)
            ->whereHas('domain', fn ($q) => $q->where('is_active', true))
            ->with(['language', 'domain'])
            ->firstOrFail();

        $domain = $translation->domain;

        $domain->load([
            'parent.translations' => fn ($q) => $q->where('language_id', $languageId),
            'children' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->with([
                'translations' => fn ($t) => $t->where('language_id', $languageId),
            ])->withCount([
                'concepts as terms_count' => function ($q2) use ($languageId): void {
                    $q2->where('concepts.status', 'published')
                        ->whereHas('translations', fn ($t) => $t->where('language_id', $languageId));
                },
            ]),
        ]);

        $domain->loadCount([
            'concepts as terms_count' => function ($q) use ($languageId): void {
                $q->where('concepts.status', 'published')
                    ->whereHas('translations', fn ($t) => $t->where('language_id', $languageId));
            },
        ]);

        $relatedDomainIds = DB::table('concept_domain as cd1')
            ->join('concept_domain as cd2', 'cd1.concept_id', '=', 'cd2.concept_id')
            ->where('cd1.domain_id', $domain->id)
            ->where('cd2.domain_id', '!=', $domain->id)
            ->selectRaw('cd2.domain_id, COUNT(*) as c')
            ->groupBy('cd2.domain_id')
            ->orderByDesc('c')
            ->limit(12)
            ->pluck('cd2.domain_id');

        $relatedDomains = collect();
        if ($relatedDomainIds->isNotEmpty()) {
            $relatedDomains = Domain::query()
                ->whereIn('id', $relatedDomainIds)
                ->where('is_active', true)
                ->with(['translations' => fn ($q) => $q->where('language_id', $languageId)])
                ->get()
                ->sortBy(fn (Domain $d) => $relatedDomainIds->search($d->id))
                ->values();
        }

        $pageTitle = $translation->name.' — '.__('Domain');
        $rawDescription = $translation->description ?? '';
        $metaDescription = Str::limit(strip_tags((string) $rawDescription), 165, '…');
        if ($metaDescription === '') {
            $metaDescription = Str::limit(
                __('Published concepts and sub-areas under :name.', ['name' => $translation->name]),
                165,
                '…',
            );
        }

        $canonical = route('domains.show', ['locale' => $locale, 'slug' => $translation->slug], absolute: true);

        $alternates = [];
        foreach (Locales::codes() as $code) {
            $peer = $domain->translationForLocale($code);
            if ($peer !== null && $peer->slug !== '') {
                $alternates[$code] = route('domains.show', ['locale' => $code, 'slug' => $peer->slug], absolute: true);
            }
        }

        $xDefaultUrl = $alternates[Locales::fallback()] ?? (count($alternates) > 0 ? reset($alternates) : $canonical);

        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $translation->name,
            'description' => Str::limit(strip_tags((string) ($translation->description ?? $metaDescription)), 5000),
            'url' => $canonical,
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => config('app.name'),
                'url' => route('home', ['locale' => $locale], absolute: true),
            ],
        ];

        $breadcrumbRows = StructuredData::domainBreadcrumbRows($domain, $locale, $languageId, $canonical);

        return view('pages.domain-show', [
            'translation' => $translation,
            'domain' => $domain,
            'locale' => $locale,
            'languageId' => $languageId,
            'relatedDomains' => $relatedDomains,
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
