<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Support\ConceptMedia;
use App\Support\FootwearConstructionMediaAuthority;
use App\Support\Editorial\TerminologyStatus;
use App\Support\Editorial\WorkflowStatus;
use App\Support\Locales;
use App\Support\ProcessGraph;
use App\Support\SemanticGraph;
use App\Support\Seo\StructuredData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class GlossaryConceptShowController extends Controller
{
    public function __invoke(Request $request, string $locale, string $slug): View
    {
        $slug = mb_strtolower($slug, 'UTF-8');

        $languageId = Language::activeIdForCode($locale);

        if ($languageId === null) {
            abort(404);
        }

        /** @var ConceptTranslation $translation */
        $translation = ConceptTranslation::query()
            ->where('slug', $slug)
            ->where('language_id', $languageId)
            ->where('status', WorkflowStatus::PUBLISHED)
            ->whereHas('concept', fn ($q) => $q->where('status', WorkflowStatus::PUBLISHED))
            ->with([
                'language',
                'validator:id,name',
                'examples' => fn ($q) => $q->orderBy('sort_order'),
                'concept.translations.language',
                'concept.domains.translations' => fn ($q) => $q->where('language_id', $languageId),
                'concept.domains.parent.translations' => fn ($q) => $q->where('language_id', $languageId),
                'concept.outgoingRelations.relatedConcept.translations' => fn ($q) => $q->where('language_id', $languageId),
                'concept.incomingRelations.concept.translations' => fn ($q) => $q->where('language_id', $languageId),
                'concept.media',
            ])
            ->firstOrFail();

        $concept = $translation->concept;

        $semanticGrouped = SemanticGraph::peerTranslationsGrouped($concept, $languageId);
        $workflowNeighbors = ProcessGraph::workflowNeighbors($concept, $languageId);
        $workflowSignals = ProcessGraph::continuitySignals($concept, $languageId);
        $readingJourneys = ProcessGraph::semanticReadingJourneys($concept, $languageId);
        $supersededBy = SemanticGraph::supersededByTranslation($concept, $languageId);
        $excludePeerIds = SemanticGraph::peerConceptIds($semanticGrouped, $supersededBy);
        $domainCoConcepts = SemanticGraph::domainContextPeers($concept, $languageId, 8, $excludePeerIds);
        $semanticCounts = [
            'synonyms' => ($semanticGrouped['synonyms'] ?? collect())->count(),
            'broader' => ($semanticGrouped['broader'] ?? collect())->count(),
            'narrower' => ($semanticGrouped['narrower'] ?? collect())->count(),
            'related' => ($semanticGrouped['related'] ?? collect())->count(),
            'industry_variants' => ($semanticGrouped['industry_variants'] ?? collect())->count(),
        ];

        $pageTitle = filled($translation->seo_title)
            ? $translation->seo_title
            : $translation->term.' — '.config('app.glossary_name');

        $rawDescription = $translation->seo_description
            ?? $translation->short_definition
            ?? $translation->full_definition
            ?? '';

        $metaDescription = Str::limit(strip_tags((string) $rawDescription), 165, '…');

        $canonical = route('glossary.concept', ['locale' => $locale, 'slug' => $translation->slug], absolute: true);

        $ogTitle = filled($translation->og_title) ? (string) $translation->og_title : null;
        $ogDescription = filled($translation->og_description)
            ? Str::limit(strip_tags((string) $translation->og_description), 200, '…')
            : null;

        $alternates = [];
        foreach (Locales::codes() as $code) {
            $peer = $concept->translationForLocale($code);
            if ($peer !== null && $peer->status === WorkflowStatus::PUBLISHED) {
                $alternates[$code] = route('glossary.concept', ['locale' => $code, 'slug' => $peer->slug], absolute: true);
            }
        }

        // Canonical overrides are only accepted when they match the computed localized
        // canonical for this page, preventing canonical/hreflang conflicts.
        $canonicalOverride = $translation->seo_canonical_url;
        if (is_string($canonicalOverride)) {
            $candidate = trim($canonicalOverride);
            if ($candidate !== '' && filter_var($candidate, FILTER_VALIDATE_URL)) {
                if ($candidate === $canonical) {
                    $canonical = $candidate;
                }
            }
        }

        $xDefaultUrl = $alternates[Locales::fallback()] ?? (count($alternates) > 0 ? reset($alternates) : $canonical);

        $seeAlso = SemanticGraph::seeAlsoUrls($locale, $semanticGrouped, $supersededBy, $domainCoConcepts);
        foreach (['upstream', 'downstream', 'route_alternatives'] as $workflowBucket) {
            foreach ($workflowNeighbors[$workflowBucket] as $peerTr) {
                $seeAlso[] = $peerTr->glossaryUrl(absolute: true);
            }
        }
        foreach ($workflowSignals['used_in_routes'] as $peerTr) {
            $seeAlso[] = $peerTr->glossaryUrl(absolute: true);
        }
        foreach ($readingJourneys as $journey) {
            foreach ($journey['items'] as $peerTr) {
                $seeAlso[] = $peerTr->glossaryUrl(absolute: true);
            }
        }
        $seeAlso = array_values(array_unique($seeAlso));
        $seeAlso = array_slice($seeAlso, 0, 24);

        $featuredMedia = $concept->getFirstMedia(Concept::COLLECTION_FEATURED);
        $ogImage = ConceptMedia::ogImageUrl($featuredMedia);
        $ogImageAlt = $featuredMedia !== null
            ? ConceptMedia::meta($featuredMedia, $locale, $translation->term)['alt']
            : null;

        $schemaImages = ConceptMedia::imageObjectUrls(
            collect($concept->getMedia(Concept::COLLECTION_FEATURED))->merge($concept->getMedia(Concept::COLLECTION_GALLERY)),
        );

        $examplesPlain = $translation->examples
            ->pluck('example')
            ->filter(fn ($t) => is_string($t) && trim($t) !== '')
            ->map(fn (string $t): string => trim($t))
            ->take(8)
            ->values()
            ->all();

        $definedTerm = StructuredData::definedTermForConcept(
            $translation,
            $concept,
            $locale,
            $canonical,
            $metaDescription,
            $seeAlso,
            $schemaImages,
            $examplesPlain,
        );

        $breadcrumbRows = StructuredData::conceptBreadcrumbRows(
            $concept,
            $translation,
            $locale,
            $languageId,
            $canonical,
        );
        $publishedTranslationCount = $concept->translations
            ->where('status', WorkflowStatus::PUBLISHED)
            ->count();
        $confidenceSignals = [
            'full_definition' => filled($translation->full_definition),
            'short_definition' => filled($translation->short_definition),
            'examples' => $translation->examples->isNotEmpty(),
            'semantic_relations' => array_sum($semanticCounts) > 0,
            'translations' => $publishedTranslationCount >= 2,
            'validation' => filled($translation->validated_at) && filled($translation->validated_by),
            'terminology_status' => in_array($translation->terminology_status, [
                TerminologyStatus::REVIEWED,
                TerminologyStatus::VALIDATED,
            ], true),
        ];
        $confidencePoints = collect($confidenceSignals)->filter()->count();
        $confidenceLevel = $confidencePoints >= 6
            ? 'high'
            : ($confidencePoints >= 4 ? 'medium' : 'low');

        $mediaItems = $concept->media;
        $mediaMeta = $mediaItems->map(fn ($media) => ConceptMedia::meta($media, $locale, $translation->term));
        $conceptKey = $concept->translationForLocale('en')?->slug ?? $translation->slug;
        $priorityMediaConcepts = array_values(array_unique(array_merge(
            FootwearConstructionMediaAuthority::PRIORITY_CONCEPT_KEYS,
            [
                'side-lasting',
                'seat-lasting',
                'blake-stitch',
                'sole-pressing',
                'heel-counter-reinforcement',
                'feather-edge',
                'gemming-rib',
                'filler-cork',
                'sockliner',
            ],
        )));
        $highValueMediaCount = $mediaMeta->filter(
            fn (array $m): bool => in_array($m['semantic_role'], ['construction', 'process', 'workflow', 'anatomy', 'machinery', 'quality'], true),
        )->count();
        $mediaTrustSignals = [
            'total' => $mediaItems->count(),
            'localized_alt' => $mediaMeta->filter(fn (array $m): bool => filled($m['alt']))->count(),
            'captioned' => $mediaMeta->filter(fn (array $m): bool => filled($m['caption']))->count(),
            'process_staged' => $mediaMeta->filter(fn (array $m): bool => filled($m['process_stage']))->count(),
            'source_documented' => $mediaMeta->filter(fn (array $m): bool => filled($m['source_label']) || filled($m['source_url']))->count(),
            'diagram_or_process' => $mediaMeta->filter(fn (array $m): bool => in_array($m['semantic_role'], ['construction', 'process', 'workflow', 'anatomy'], true))->count(),
            'high_value_assets' => $highValueMediaCount,
            'priority_target' => in_array($conceptKey, $priorityMediaConcepts, true),
            'priority_minimum_met' => ! in_array($conceptKey, $priorityMediaConcepts, true) || $highValueMediaCount >= 2,
        ];

        $jsonLdBlocks = [
            $definedTerm,
            StructuredData::breadcrumbList($breadcrumbRows),
        ];

        return view('pages.glossary-concept', [
            'translation' => $translation,
            'concept' => $concept,
            'languageId' => $languageId,
            'locale' => $locale,
            'semanticGrouped' => $semanticGrouped,
            'workflowNeighbors' => $workflowNeighbors,
            'workflowSignals' => $workflowSignals,
            'readingJourneys' => $readingJourneys,
            'supersededBy' => $supersededBy,
            'domainCoConcepts' => $domainCoConcepts,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
            'canonical' => $canonical,
            'ogUrl' => $canonical,
            'ogTitle' => $ogTitle,
            'ogDescription' => $ogDescription,
            'alternates' => $alternates,
            'xDefaultUrl' => $xDefaultUrl,
            'structuredData' => null,
            'jsonLdBlocks' => $jsonLdBlocks,
            'breadcrumbs' => $breadcrumbRows,
            'semanticCounts' => $semanticCounts,
            'confidenceLevel' => $confidenceLevel,
            'confidenceSignals' => $confidenceSignals,
            'publishedTranslationCount' => $publishedTranslationCount,
            'ogImage' => $ogImage,
            'ogImageAlt' => $ogImageAlt,
            'mediaTrustSignals' => $mediaTrustSignals,
        ]);
    }
}
