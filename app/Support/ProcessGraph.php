<?php

namespace App\Support;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Support\Editorial\WorkflowStatus;
use Illuminate\Support\Collection;

/**
 * Workflow-aware process neighbors from directed {@see ConceptRelation} rows.
 *
 * Uses asymmetric `related` edges as upstream/downstream (manufacturing sequence).
 * Bidirectional `related` edges on route concepts surface as route alternatives.
 * No graph DB, recursion, or separate process-engine types.
 */
final class ProcessGraph
{
    /**
     * Manufacturing-route concept slugs (EN) used for contextual route forks.
     *
     * @var list<string>
     */
    public const array ROUTE_CONCEPT_SLUGS = [
        'goodyear-welt',
        'blake-stitch',
        'stitchdown-construction',
        'cemented-construction',
        'strobel-stitch',
        'board-lasted-construction',
        'cupsole-cementing',
    ];

    /**
     * Lightweight semantic reading journeys (workflow exploration, not courseware).
     *
     * @var array<string, list<string>>
     */
    /**
     * Priority slugs that receive a single upstream–downstream line-position phrase for search continuity.
     *
     * @var list<string>
     */
    private const array PRIORITY_CONTINUITY_SLUGS = [
        'roughing',
        'primer-coat',
        'heat-activation',
        'sole-pressing',
        'holdfast-stitch',
        'channel-stitching',
        'lockstitch-seam',
        'waist-shaping',
        'shank-reinforcement',
        'seat-lasting',
        'insole-board',
        'gemming-rib',
        'welt-channel',
        'filler-cork',
        'rib-attaching',
        'channeling',
        'sidewall-trimming',
        'edge-ink-build',
    ];

    /**
     * Localized manufacturing-stage family labels (keys match {@see STAGE_FAMILY_SLUGS}).
     *
     * @var array<string, array<string, string>>
     */
    private const array STAGE_FAMILY_LABELS = [
        'lasting systems' => [
            'en' => 'lasting systems',
            'pt' => 'sistemas de moldação',
            'fr' => 'systèmes de montage sur forme',
            'de' => 'Aufziehsysteme',
            'it' => 'sistemi di montaggio su forma',
            'es' => 'sistemas de montado en horma',
        ],
        'welted construction' => [
            'en' => 'welted construction',
            'pt' => 'construção com vira',
            'fr' => 'construction trépointée',
            'de' => 'Rahmenkonstruktion',
            'it' => 'costruzione a guardolo',
            'es' => 'construcción con cerco',
        ],
        'cemented construction' => [
            'en' => 'cemented construction',
            'pt' => 'construção colada',
            'fr' => 'construction cimentée',
            'de' => 'geklebte Konstruktion',
            'it' => 'costruzione cementata',
            'es' => 'construcción cementada',
        ],
        'strobel workflows' => [
            'en' => 'Strobel workflows',
            'pt' => 'fluxos Strobel',
            'fr' => 'flux Strobel',
            'de' => 'Strobel-Workflows',
            'it' => 'flussi Strobel',
            'es' => 'flujos Strobel',
        ],
        'reinforcement systems' => [
            'en' => 'reinforcement systems',
            'pt' => 'sistemas de reforço',
            'fr' => 'systèmes de renfort',
            'de' => 'Verstärkungssysteme',
            'it' => 'sistemi di rinforzo',
            'es' => 'sistemas de refuerzo',
        ],
        'stitching systems' => [
            'en' => 'stitching systems',
            'pt' => 'sistemas de costura',
            'fr' => 'systèmes de couture',
            'de' => 'Nähtsysteme',
            'it' => 'sistemi di cucitura',
            'es' => 'sistemas de costura',
        ],
        'bottoming workflows' => [
            'en' => 'bottoming workflows',
            'pt' => 'fluxos de montagem de fundo',
            'fr' => 'flux de montage de fond',
            'de' => 'Bottoming-Workflows',
            'it' => 'flussi di montaggio fondo',
            'es' => 'flujos de montaje de fondo',
        ],
    ];

    private const array JOURNEY_DEFINITIONS = [
        'journey_understanding_lasting' => [
            'lasting',
            'shoe-last',
            'upper',
            'toe-lasting',
            'side-lasting',
            'back-part-lasting',
            'seat-lasting',
        ],
        'journey_understanding_bottoming' => [
            'bottoming',
            'seat-lasting',
            'roughing',
            'primer-coat',
            'cementing',
            'heat-activation',
            'sole-pressing',
            'bond-line',
            'outsole-cure-window',
            'sidewall-trimming',
            'edge-ink-build',
        ],
        'journey_understanding_cemented_construction' => [
            'seat-lasting',
            'cemented-construction',
            'roughing',
            'primer-coat',
            'cement-open-time',
            'adhesive-transfer-latency',
            'cementing',
            'heat-activation',
            'sole-pressing',
            'bond-line',
        ],
        'journey_understanding_welted_construction' => [
            'seat-lasting',
            'goodyear-welt',
            'insole-board',
            'rib-attaching',
            'channeling',
            'gemming-rib',
            'holdfast-stitch',
            'filler-cork',
            'waist-shaping',
            'welt-channel',
            'channel-stitching',
            'welt-stitch-penetration',
        ],
        'journey_understanding_stitching_systems' => [
            'holdfast-stitch',
            'channel-stitching',
            'blake-stitch',
            'strobel-stitch',
            'inseam-stitch',
            'lockstitch-seam',
            'stitchdown-construction',
        ],
        'journey_understanding_strobel_assembly' => [
            'strobel-board',
            'strobel-stitch',
            'lasting',
            'side-lasting',
            'seat-lasting',
            'cemented-construction',
        ],
        'journey_understanding_reinforcement_systems' => [
            'toe-puff',
            'heel-counter',
            'heel-counter-reinforcement',
            'shank-reinforcement',
            'topline-reinforcement',
            'internal-footbed-stack',
        ],
    ];

    /**
     * Lightweight manufacturing-stage families for contextual readability.
     *
     * @var array<string, list<string>>
     */
    private const array STAGE_FAMILY_SLUGS = [
        'lasting systems' => [
            'lasting',
            'toe-lasting',
            'side-lasting',
            'back-part-lasting',
            'seat-lasting',
            'lasting-margin',
            'lasting-tuck',
            'tuck',
        ],
        'welted construction' => [
            'goodyear-welt',
            'welt',
            'insole-board',
            'rib-attaching',
            'channeling',
            'gemming-rib',
            'holdfast-stitch',
            'welt-channel',
            'channel-stitching',
            'welt-stitch-penetration',
            'filler-cork',
            'waist-shaping',
        ],
        'cemented construction' => [
            'cemented-construction',
            'roughing',
            'primer-coat',
            'cementing',
            'heat-activation',
            'sole-pressing',
            'bond-line',
            'outsole-cure-window',
        ],
        'strobel workflows' => [
            'strobel-board',
            'strobel-stitch',
            'cemented-construction',
            'cupsole-cementing',
        ],
        'reinforcement systems' => [
            'toe-puff',
            'heel-counter',
            'heel-counter-reinforcement',
            'shank-reinforcement',
            'topline-reinforcement',
            'internal-footbed-stack',
        ],
        'stitching systems' => [
            'blake-stitch',
            'channel-stitching',
            'holdfast-stitch',
            'inseam-stitch',
            'stitchdown-construction',
            'strobel-stitch',
            'lockstitch-seam',
        ],
        'bottoming workflows' => [
            'bottoming',
            'cemented-construction',
            'goodyear-welt',
            'blake-stitch',
            'stitchdown-construction',
            'cupsole-cementing',
            'outsole',
            'bond-line',
            'edge-ink-build',
            'sidewall-trimming',
        ],
    ];

    /**
     * @return array{
     *     upstream: Collection<int, ConceptTranslation>,
     *     downstream: Collection<int, ConceptTranslation>,
     *     route_alternatives: Collection<int, ConceptTranslation>,
     * }
     */
    public static function workflowNeighbors(Concept $concept, int $languageId): array
    {
        $outIds = self::outgoingRelatedPeerIds($concept);
        $inIds = self::incomingRelatedPeerIds($concept);
        $bidirectional = array_values(array_intersect($outIds, $inIds));
        $downstreamIds = array_values(array_diff($outIds, $inIds));
        $upstreamIds = array_values(array_diff($inIds, $outIds));

        $routeIds = [];
        foreach ($bidirectional as $peerId) {
            if (self::peerIsRouteConcept($concept, $peerId)) {
                $routeIds[] = $peerId;
            }
        }

        return [
            'upstream' => self::translationsForPeerIds($concept, $upstreamIds, $languageId, incoming: true),
            'downstream' => self::translationsForPeerIds($concept, $downstreamIds, $languageId, incoming: false),
            'route_alternatives' => self::translationsForPeerIds($concept, $routeIds, $languageId, incoming: true),
        ];
    }

    /**
     * Lightweight continuity signals for glossary rendering.
     *
     * @return array{
     *     follows: Collection<int, ConceptTranslation>,
     *     precedes: Collection<int, ConceptTranslation>,
     *     alternatives: Collection<int, ConceptTranslation>,
     *     used_in_routes: Collection<int, ConceptTranslation>,
     *     often_paired_with: Collection<int, ConceptTranslation>,
     *     related_stages: list<string>,
     * }
     */
    public static function continuitySignals(Concept $concept, int $languageId): array
    {
        $neighbors = self::workflowNeighbors($concept, $languageId);
        $currentIsRoute = self::conceptMatchesRouteSlug($concept);
        $routePeers = self::routePeerTranslations($concept, $languageId);

        return [
            'follows' => $neighbors['upstream'],
            'precedes' => $neighbors['downstream'],
            'alternatives' => $neighbors['route_alternatives'],
            'used_in_routes' => $currentIsRoute ? collect() : $routePeers,
            'often_paired_with' => self::pairedPeerTranslations($concept, $languageId),
            'related_stages' => self::relatedStageLabels($concept, $languageId),
        ];
    }

    /**
     * Semantic reading journeys relevant to the current concept context.
     *
     * @return Collection<int, array{
     *     key: string,
     *     items: Collection<int, ConceptTranslation>,
     * }>
     */
    public static function semanticReadingJourneys(Concept $concept, int $languageId, int $max = 3): Collection
    {
        if ($max <= 0) {
            return collect();
        }

        $contextSlugs = self::contextSlugsForConcept($concept, $languageId);
        if ($contextSlugs === []) {
            return collect();
        }

        $allJourneySlugs = collect(self::JOURNEY_DEFINITIONS)->flatten()->unique()->values()->all();
        $journeyConceptBySlug = self::journeyConceptMap($allJourneySlugs, $languageId);

        $journeys = collect();
        foreach (self::JOURNEY_DEFINITIONS as $journeyKey => $journeySlugs) {
            if (array_intersect($journeySlugs, $contextSlugs) === []) {
                continue;
            }

            $items = collect();
            foreach ($journeySlugs as $slug) {
                $tr = $journeyConceptBySlug[$slug] ?? null;
                if ($tr !== null) {
                    $items->push($tr);
                }
            }

            if ($items->count() < 3) {
                continue;
            }

            $journeys->push([
                'key' => $journeyKey,
                'items' => $items,
            ]);
        }

        return $journeys->take($max)->values();
    }

    /**
     * Concept IDs to omit from the flat associative {@see SemanticGraph} `related` bucket.
     *
     * @return list<int>
     */
    public static function associativeRelatedPeerIdsToExclude(Concept $concept): array
    {
        $outIds = self::outgoingRelatedPeerIds($concept);
        $inIds = self::incomingRelatedPeerIds($concept);
        $bidirectional = array_intersect($outIds, $inIds);

        $routeIds = [];
        foreach ($bidirectional as $peerId) {
            if (self::peerIsRouteConcept($concept, $peerId)) {
                $routeIds[] = $peerId;
            }
        }

        $asymmetric = array_merge(
            array_diff($outIds, $inIds),
            array_diff($inIds, $outIds),
        );

        return array_values(array_unique(array_merge($asymmetric, $routeIds)));
    }

    /**
     * @return list<string>
     */
    public static function upstreamTerms(Concept $concept, int $languageId): array
    {
        return self::workflowNeighbors($concept, $languageId)['upstream']
            ->pluck('term')
            ->filter(fn ($t) => is_string($t) && $t !== '')
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    public static function downstreamTerms(Concept $concept, int $languageId): array
    {
        return self::workflowNeighbors($concept, $languageId)['downstream']
            ->pluck('term')
            ->filter(fn ($t) => is_string($t) && $t !== '')
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    public static function routeContextTerms(Concept $concept, int $languageId): array
    {
        $signals = self::continuitySignals($concept, $languageId);

        return collect([
            $signals['alternatives'],
            $signals['used_in_routes'],
            $signals['often_paired_with'],
        ])->flatten(1)
            ->pluck('term')
            ->filter(fn ($t) => is_string($t) && $t !== '')
            ->unique()
            ->values()
            ->take(4)
            ->all();
    }

    /**
     * Slugs that receive a single upstream–downstream line-position guidance phrase.
     *
     * @return list<string>
     */
    public static function continuitySlugsWithLinePosition(): array
    {
        return self::PRIORITY_CONTINUITY_SLUGS;
    }

    /**
     * Human-readable workflow guidance phrases for search discoverability.
     *
     * @return list<string>
     */
    public static function contextualGuidancePhrases(Concept $concept, int $languageId): array
    {
        $signals = self::continuitySignals($concept, $languageId);
        $term = self::publishedTranslation($concept, $languageId)?->term;

        if (! is_string($term) || $term === '') {
            return [];
        }

        $locale = self::guidanceLocale($languageId);
        $slug = self::publishedTranslation($concept, $languageId)?->slug;
        $follows = self::termListFromTranslations($signals['follows'] ?? collect(), 2);
        $precedes = self::termListFromTranslations($signals['precedes'] ?? collect(), 2);

        $phrases = [];
        $hasLinePosition = false;
        if (
            is_string($slug)
            && in_array($slug, self::PRIORITY_CONTINUITY_SLUGS, true)
            && $follows !== []
            && $precedes !== []
        ) {
            $phrases[] = self::contextualGuidancePhrase($locale, 'line_position', $term, $follows[0], $precedes[0]);
            $hasLinePosition = true;
            $follows = [];
            $precedes = [];
        }

        foreach ($follows as $peer) {
            $phrases[] = self::contextualGuidancePhrase($locale, 'follows', $term, $peer);
        }
        foreach ($precedes as $peer) {
            $phrases[] = self::contextualGuidancePhrase($locale, 'precedes', $term, $peer);
        }
        foreach (self::termListFromTranslations($signals['used_in_routes'] ?? collect(), 1) as $peer) {
            $phrases[] = self::contextualGuidancePhrase($locale, 'used_in', $term, $peer);
        }
        if (! $hasLinePosition) {
            foreach (self::termListFromTranslations($signals['alternatives'] ?? collect(), 1) as $peer) {
                $phrases[] = self::contextualGuidancePhrase($locale, 'alternative', $term, $peer);
            }
            foreach (self::termListFromTranslations($signals['often_paired_with'] ?? collect(), 1) as $peer) {
                $phrases[] = self::contextualGuidancePhrase($locale, 'often_paired', $term, $peer);
            }
            $primaryStage = ($signals['related_stages'] ?? [])[0] ?? null;
            if (is_string($primaryStage) && $primaryStage !== '') {
                $phrases[] = self::contextualGuidancePhrase($locale, 'stage', $term, $primaryStage);
            }
        }

        return collect($phrases)
            ->unique()
            ->values()
            ->take(7)
            ->all();
    }

    /**
     * @return list<string>
     */
    public static function journeyContextTerms(Concept $concept, int $languageId): array
    {
        return self::semanticReadingJourneys($concept, $languageId, max: 3)
            ->pluck('items')
            ->flatten(1)
            ->pluck('term')
            ->filter(fn ($t) => is_string($t) && $t !== '')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return list<int>
     */
    private static function outgoingRelatedPeerIds(Concept $concept): array
    {
        $ids = [];
        foreach ($concept->outgoingRelations as $rel) {
            if (self::isAssociativeRelatedType($rel->relation_type)) {
                $ids[] = (int) $rel->related_concept_id;
            }
        }

        return $ids;
    }

    /**
     * @return list<int>
     */
    private static function incomingRelatedPeerIds(Concept $concept): array
    {
        $ids = [];
        foreach ($concept->incomingRelations as $rel) {
            if (self::isAssociativeRelatedType($rel->relation_type)) {
                $ids[] = (int) $rel->concept_id;
            }
        }

        return $ids;
    }

    private static function isAssociativeRelatedType(string $type): bool
    {
        return SemanticGraph::normalizedType($type) === 'related';
    }

    private static function peerIsRouteConcept(Concept $concept, int $peerConceptId): bool
    {
        foreach ($concept->outgoingRelations as $rel) {
            if ((int) $rel->related_concept_id !== $peerConceptId) {
                continue;
            }
            if (self::conceptMatchesRouteSlug($rel->relatedConcept)) {
                return true;
            }
        }
        foreach ($concept->incomingRelations as $rel) {
            if ((int) $rel->concept_id !== $peerConceptId) {
                continue;
            }
            if (self::conceptMatchesRouteSlug($rel->concept)) {
                return true;
            }
        }

        return false;
    }

    private static function conceptMatchesRouteSlug(?Concept $peer): bool
    {
        if ($peer === null) {
            return false;
        }

        foreach ($peer->translations as $tr) {
            $slug = $tr->slug;
            if (is_string($slug) && in_array($slug, self::ROUTE_CONCEPT_SLUGS, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection<int, ConceptTranslation>
     */
    private static function routePeerTranslations(Concept $concept, int $languageId): Collection
    {
        $items = collect();

        foreach ($concept->outgoingRelations as $rel) {
            if (! self::isAssociativeRelatedType($rel->relation_type)) {
                continue;
            }
            if (! self::conceptMatchesRouteSlug($rel->relatedConcept)) {
                continue;
            }
            $tr = self::publishedTranslation($rel->relatedConcept, $languageId);
            if ($tr !== null) {
                $items->push($tr);
            }
        }

        foreach ($concept->incomingRelations as $rel) {
            if (! self::isAssociativeRelatedType($rel->relation_type)) {
                continue;
            }
            if (! self::conceptMatchesRouteSlug($rel->concept)) {
                continue;
            }
            $tr = self::publishedTranslation($rel->concept, $languageId);
            if ($tr !== null) {
                $items->push($tr);
            }
        }

        return $items
            ->unique(fn (ConceptTranslation $t) => $t->concept_id)
            ->values();
    }

    /**
     * @return Collection<int, ConceptTranslation>
     */
    private static function pairedPeerTranslations(Concept $concept, int $languageId): Collection
    {
        $outIds = self::outgoingRelatedPeerIds($concept);
        $inIds = self::incomingRelatedPeerIds($concept);
        $bidirectional = array_values(array_intersect($outIds, $inIds));

        $pairIds = [];
        foreach ($bidirectional as $peerId) {
            if (! self::peerIsRouteConcept($concept, $peerId)) {
                $pairIds[] = $peerId;
            }
        }

        return self::translationsForPeerIds($concept, $pairIds, $languageId, incoming: true);
    }

    /**
     * @return list<string>
     */
    private static function contextSlugsForConcept(Concept $concept, int $languageId): array
    {
        $signals = self::continuitySignals($concept, $languageId);

        return collect([
            self::conceptSlugs($concept),
            $signals['follows']->pluck('slug')->all(),
            $signals['precedes']->pluck('slug')->all(),
            $signals['alternatives']->pluck('slug')->all(),
            $signals['used_in_routes']->pluck('slug')->all(),
            $signals['often_paired_with']->pluck('slug')->all(),
        ])->flatten()
            ->filter(fn ($slug) => is_string($slug) && $slug !== '')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    private static function relatedStageLabels(Concept $concept, int $languageId): array
    {
        $locale = self::guidanceLocale($languageId);
        $slug = self::publishedTranslation($concept, $languageId)?->slug;

        $labels = [];
        if (is_string($slug) && $slug !== '') {
            foreach (self::STAGE_FAMILY_SLUGS as $familyKey => $familySlugs) {
                if (in_array($slug, $familySlugs, true)) {
                    $labels[] = self::localizedStageFamilyLabel($familyKey, $locale);
                }
            }
        }

        if ($labels !== []) {
            return [array_values(array_unique($labels))[0]];
        }

        $neighbors = self::workflowNeighbors($concept, $languageId);
        $routePeers = self::routePeerTranslations($concept, $languageId);
        $paired = self::pairedPeerTranslations($concept, $languageId);

        $slugs = collect([
            self::conceptSlugs($concept),
            $neighbors['upstream']->pluck('slug')->all(),
            $neighbors['downstream']->pluck('slug')->all(),
            $neighbors['route_alternatives']->pluck('slug')->all(),
            $routePeers->pluck('slug')->all(),
            $paired->pluck('slug')->all(),
        ])->flatten()
            ->filter(fn ($candidate) => is_string($candidate) && $candidate !== '')
            ->unique()
            ->values()
            ->all();

        $inferred = collect(self::STAGE_FAMILY_SLUGS)
            ->filter(fn (array $familySlugs) => array_intersect($familySlugs, $slugs) !== [])
            ->keys()
            ->map(fn (string $familyKey) => self::localizedStageFamilyLabel($familyKey, $locale))
            ->reject(fn (string $label) => in_array($label, $labels, true))
            ->take(1)
            ->values()
            ->all();

        return array_values(array_unique(array_merge($labels, $inferred))) !== []
            ? [array_values(array_unique(array_merge($labels, $inferred)))[0]]
            : [];
    }

    private static function localizedStageFamilyLabel(string $familyKey, string $locale): string
    {
        $labels = self::STAGE_FAMILY_LABELS[$familyKey] ?? null;
        if (! is_array($labels)) {
            return $familyKey;
        }

        return $labels[$locale] ?? $labels['en'] ?? $familyKey;
    }

    /**
     * @return list<string>
     */
    private static function termListFromTranslations(Collection $items, int $max = 3): array
    {
        if ($max <= 0) {
            return [];
        }

        return $items
            ->pluck('term')
            ->filter(fn ($term) => is_string($term) && $term !== '')
            ->take($max)
            ->values()
            ->all();
    }

    /**
     * @param  list<string>  $journeySlugs
     * @return array<string, ConceptTranslation>
     */
    private static function journeyConceptMap(array $journeySlugs, int $languageId): array
    {
        if ($journeySlugs === []) {
            return [];
        }

        $rows = ConceptTranslation::query()
            ->whereIn('slug', $journeySlugs)
            ->where('status', WorkflowStatus::PUBLISHED)
            ->whereHas('concept', fn ($q) => $q->where('status', WorkflowStatus::PUBLISHED))
            ->with([
                'concept.translations' => fn ($q) => $q
                    ->where('status', WorkflowStatus::PUBLISHED)
                    ->where('language_id', $languageId),
            ])
            ->get();

        $mapped = [];
        foreach ($rows as $seedTranslation) {
            $localized = $seedTranslation->concept?->translations?->first();
            if ($localized === null) {
                continue;
            }
            $mapped[$seedTranslation->slug] = $localized;
        }

        return $mapped;
    }

    /**
     * @return list<string>
     */
    private static function conceptSlugs(Concept $concept): array
    {
        return $concept->translations
            ->pluck('slug')
            ->filter(fn ($slug) => is_string($slug) && $slug !== '')
            ->values()
            ->all();
    }

    /**
     * @param  list<int>  $peerConceptIds
     * @return Collection<int, ConceptTranslation>
     */
    private static function translationsForPeerIds(
        Concept $concept,
        array $peerConceptIds,
        int $languageId,
        bool $incoming,
    ): Collection {
        if ($peerConceptIds === []) {
            return collect();
        }

        $order = array_flip($peerConceptIds);
        $items = collect();

        if ($incoming) {
            foreach ($concept->incomingRelations as $rel) {
                if (! in_array((int) $rel->concept_id, $peerConceptIds, true)) {
                    continue;
                }
                $tr = self::publishedTranslation($rel->concept, $languageId);
                if ($tr !== null) {
                    $items->push($tr);
                }
            }
        } else {
            foreach ($concept->outgoingRelations as $rel) {
                if (! in_array((int) $rel->related_concept_id, $peerConceptIds, true)) {
                    continue;
                }
                $tr = self::publishedTranslation($rel->relatedConcept, $languageId);
                if ($tr !== null) {
                    $items->push($tr);
                }
            }
        }

        return $items
            ->unique(fn (ConceptTranslation $t) => $t->concept_id)
            ->sortBy(fn (ConceptTranslation $t) => $order[$t->concept_id] ?? PHP_INT_MAX)
            ->values();
    }

    private static function publishedTranslation(?Concept $peer, int $languageId): ?ConceptTranslation
    {
        if ($peer === null || $peer->status !== 'published') {
            return null;
        }

        /** @var ConceptTranslation|null $tr */
        $tr = $peer->translations->firstWhere('language_id', $languageId);
        if ($tr === null || $tr->status !== WorkflowStatus::PUBLISHED) {
            return null;
        }

        return $tr;
    }

    private static function guidanceLocale(int $languageId): string
    {
        $code = Language::query()->whereKey($languageId)->value('code');

        return is_string($code) && $code !== '' ? $code : 'en';
    }

    private static function contextualGuidancePhrase(string $locale, string $kind, string $term, string $peer, ?string $peer2 = null): string
    {
        return match ($kind) {
            'line_position' => match ($locale) {
                'de' => sprintf('%s in der Fertigungsfolge nach %s, vor %s', $term, $peer, $peer2 ?? ''),
                'fr' => sprintf('%s dans la filière après %s, avant %s', $term, $peer, $peer2 ?? ''),
                'it' => sprintf('%s in filiera dopo %s, prima di %s', $term, $peer, $peer2 ?? ''),
                'pt' => sprintf('%s na sequência após %s, antes de %s', $term, $peer, $peer2 ?? ''),
                'es' => sprintf('%s en secuencia después de %s, antes de %s', $term, $peer, $peer2 ?? ''),
                default => sprintf('%s after %s, before %s', $term, $peer, $peer2 ?? ''),
            },
            'follows' => match ($locale) {
                'de' => sprintf('%s folgt auf %s', $term, $peer),
                'fr' => sprintf('%s suit %s dans la filière', $term, $peer),
                'it' => sprintf('%s segue %s', $term, $peer),
                'pt' => sprintf('%s segue %s', $term, $peer),
                'es' => sprintf('%s sigue a %s', $term, $peer),
                default => sprintf('%s follows %s', $term, $peer),
            },
            'precedes' => match ($locale) {
                'de' => sprintf('%s liegt vor %s', $term, $peer),
                'fr' => sprintf('%s précède %s', $term, $peer),
                'it' => sprintf('%s precede %s', $term, $peer),
                'pt' => sprintf('%s precede %s', $term, $peer),
                'es' => sprintf('%s precede a %s', $term, $peer),
                default => sprintf('%s precedes %s', $term, $peer),
            },
            'used_in' => match ($locale) {
                'de' => sprintf('%s in der Fertigungsroute %s', $term, $peer),
                'fr' => sprintf('%s sur la filière %s', $term, $peer),
                'it' => sprintf('%s nella filiera %s', $term, $peer),
                'pt' => sprintf('%s na rota de fabrico %s', $term, $peer),
                'es' => sprintf('%s en la filiera %s', $term, $peer),
                default => sprintf('%s in the %s route', $term, $peer),
            },
            'alternative' => match ($locale) {
                'de' => sprintf('%s — Routenabgleich mit %s', $term, $peer),
                'fr' => sprintf('%s — bifurcation vers %s', $term, $peer),
                'it' => sprintf('%s — biforcazione verso %s', $term, $peer),
                'pt' => sprintf('%s — desvio para %s', $term, $peer),
                'es' => sprintf('%s — bifurcación hacia %s', $term, $peer),
                default => sprintf('%s — route fork compares %s', $term, $peer),
            },
            'often_paired' => match ($locale) {
                'de' => sprintf('%s oft mit %s gekoppelt', $term, $peer),
                'fr' => sprintf('%s couplé à %s', $term, $peer),
                'it' => sprintf('%s accoppiato a %s', $term, $peer),
                'pt' => sprintf('%s acoplado a %s', $term, $peer),
                'es' => sprintf('%s emparejado con %s', $term, $peer),
                default => sprintf('%s paired with %s', $term, $peer),
            },
            'stage' => match ($locale) {
                'de' => sprintf('%s — %s', $term, $peer),
                'fr' => sprintf('%s — %s', $term, $peer),
                'it' => sprintf('%s — %s', $term, $peer),
                'pt' => sprintf('%s — %s', $term, $peer),
                'es' => sprintf('%s — %s', $term, $peer),
                default => sprintf('%s — %s', $term, $peer),
            },
            default => sprintf('%s %s %s', $term, $kind, $peer),
        };
    }
}
