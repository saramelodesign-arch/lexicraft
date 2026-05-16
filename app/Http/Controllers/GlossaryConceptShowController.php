<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Support\ConceptMedia;
use App\Support\Editorial\WorkflowStatus;
use App\Support\Locales;
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
                'examples' => fn ($q) => $q->orderBy('sort_order'),
                'concept.translations.language',
                'concept.domains.translations' => fn ($q) => $q->where('language_id', $languageId),
                'concept.outgoingRelations.relatedConcept.translations' => fn ($q) => $q->where('language_id', $languageId),
                'concept.incomingRelations.concept.translations' => fn ($q) => $q->where('language_id', $languageId),
                'concept.media',
            ])
            ->firstOrFail();

        $concept = $translation->concept;

        $semanticGrouped = SemanticGraph::peerTranslationsGrouped($concept, $languageId);
        $supersededBy = SemanticGraph::supersededByTranslation($concept, $languageId);
        $excludePeerIds = SemanticGraph::peerConceptIds($semanticGrouped, $supersededBy);
        $domainCoConcepts = SemanticGraph::domainContextPeers($concept, $languageId, 8, $excludePeerIds);

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
            'ogImage' => $ogImage,
            'ogImageAlt' => $ogImageAlt,
        ]);
    }
}
