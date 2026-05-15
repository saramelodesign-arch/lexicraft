<?php

namespace App\Support;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Support\Editorial\WorkflowStatus;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

/**
 * Concept-centric semantic graph presentation (no graph DB). Relation rows are stored on
 * {@see ConceptRelation}; this class merges outgoing + incoming edges for bidirectional UX and
 * groups peers by ontology-style sections for the glossary concept page.
 */
final class SemanticGraph
{
    /**
     * Allowed `relation_type` values persisted on {@see ConceptRelation}.
     * `see_also` is accepted for legacy rows and normalized to `related` for grouping.
     */
    public const array STORED_TYPES = [
        'synonym',
        'related',
        'broader',
        'narrower',
        'deprecated',
        'industry_variant',
        'see_also',
    ];

    public static function normalizedType(string $type): string
    {
        return $type === 'see_also' ? 'related' : $type;
    }

    public static function isAllowedStoredType(string $type): bool
    {
        return in_array($type, self::STORED_TYPES, true);
    }

    /**
     * @throws ValidationException
     */
    public static function assertAllowedStoredType(string $type): void
    {
        if (! self::isAllowedStoredType($type)) {
            throw ValidationException::withMessages([
                'relation_type' => __('Unsupported concept relation type: :type', ['type' => $type]),
            ]);
        }
    }

    /**
     * @return array{
     *     synonyms: Collection<int, ConceptTranslation>,
     *     broader: Collection<int, ConceptTranslation>,
     *     narrower: Collection<int, ConceptTranslation>,
     *     related: Collection<int, ConceptTranslation>,
     *     industry_variants: Collection<int, ConceptTranslation>,
     *     deprecated: Collection<int, ConceptTranslation>,
     * }
     */
    public static function peerTranslationsGrouped(Concept $concept, int $languageId): array
    {
        $buckets = [
            'synonyms' => collect(),
            'broader' => collect(),
            'narrower' => collect(),
            'related' => collect(),
            'industry_variants' => collect(),
            'deprecated' => collect(),
        ];

        $seen = [
            'synonyms' => [],
            'broader' => [],
            'narrower' => [],
            'related' => [],
            'industry_variants' => [],
            'deprecated' => [],
        ];

        $push = function (string $section, ?Concept $peer) use (&$buckets, &$seen, $languageId): void {
            if ($peer === null || $peer->status !== 'published') {
                return;
            }

            /** @var ConceptTranslation|null $tr */
            $tr = $peer->translations->firstWhere('language_id', $languageId);
            if ($tr === null || $tr->status !== WorkflowStatus::PUBLISHED) {
                return;
            }

            $cid = $peer->id;
            if (isset($seen[$section][$cid])) {
                return;
            }
            $seen[$section][$cid] = true;
            $buckets[$section]->push($tr);
        };

        foreach ($concept->outgoingRelations as $rel) {
            $type = self::normalizedType($rel->relation_type);
            $peer = $rel->relatedConcept;
            match ($type) {
                'synonym' => $push('synonyms', $peer),
                'broader' => $push('broader', $peer),
                'narrower' => $push('narrower', $peer),
                'related' => $push('related', $peer),
                'industry_variant' => $push('industry_variants', $peer),
                'deprecated' => $push('deprecated', $peer),
                default => null,
            };
        }

        foreach ($concept->incomingRelations as $rel) {
            $type = self::normalizedType($rel->relation_type);
            $peer = $rel->concept;
            match ($type) {
                'synonym' => $push('synonyms', $peer),
                'broader' => $push('narrower', $peer),
                'narrower' => $push('broader', $peer),
                'related' => $push('related', $peer),
                'industry_variant' => $push('industry_variants', $peer),
                'deprecated' => null,
                default => null,
            };
        }

        foreach (array_keys($buckets) as $key) {
            $buckets[$key] = $buckets[$key]->sortBy(fn (ConceptTranslation $t) => mb_strtolower($t->term, 'UTF-8'))->values();
        }

        return $buckets;
    }

    public static function supersededByTranslation(Concept $concept, int $languageId): ?ConceptTranslation
    {
        foreach ($concept->incomingRelations as $rel) {
            if (self::normalizedType($rel->relation_type) !== 'deprecated') {
                continue;
            }
            $replacement = $rel->concept;
            if ($replacement === null || $replacement->status !== 'published') {
                continue;
            }

            return $replacement->translations->firstWhere('language_id', $languageId);
        }

        return null;
    }

    /**
     * @param  array<string, Collection<int, ConceptTranslation>>  $grouped
     * @return list<int>
     */
    public static function peerConceptIds(array $grouped, ?ConceptTranslation $supersededBy): array
    {
        $ids = [];
        foreach ($grouped as $collection) {
            if (! $collection instanceof Collection) {
                continue;
            }
            foreach ($collection as $tr) {
                $ids[] = $tr->concept_id;
            }
        }
        if ($supersededBy !== null) {
            $ids[] = $supersededBy->concept_id;
        }

        return array_values(array_unique(array_filter($ids)));
    }

    /**
     * Published concepts sharing domains with $concept, excluding explicit semantic peers.
     *
     * @param  list<int>  $excludeConceptIds
     * @return Collection<int, ConceptTranslation>
     */
    public static function domainContextPeers(Concept $concept, int $languageId, int $limit = 8, array $excludeConceptIds = []): Collection
    {
        $domainIds = $concept->domains->pluck('id')->all();
        if ($domainIds === []) {
            return collect();
        }

        $exclude = array_unique(array_merge([$concept->id], $excludeConceptIds));

        $rows = Concept::query()
            ->where('status', 'published')
            ->whereKeyNot($exclude)
            ->whereHas('domains', fn ($q) => $q->whereIn('domains.id', $domainIds))
            ->whereHas('translations', fn ($q) => $q->where('language_id', $languageId)->where('status', WorkflowStatus::PUBLISHED))
            ->with(['translations' => fn ($q) => $q->where('language_id', $languageId)->where('status', WorkflowStatus::PUBLISHED)])
            ->withCount([
                'domains as shared_domain_count' => fn ($q) => $q->whereIn('domains.id', $domainIds),
            ])
            ->orderByDesc('shared_domain_count')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        return $rows
            ->map(fn (Concept $c) => $c->translations
                ->where('language_id', $languageId)
                ->firstWhere('status', WorkflowStatus::PUBLISHED))
            ->filter()
            ->values();
    }

    /**
     * Absolute glossary URLs for JSON-LD `seeAlso` (internal semantic linking).
     *
     * @param  array<string, Collection<int, ConceptTranslation>>  $grouped
     * @return list<string>
     */
    public static function seeAlsoUrls(string $locale, array $grouped, ?ConceptTranslation $supersededBy, Collection $domainPeers, int $max = 24): array
    {
        $urls = [];
        foreach ($grouped as $collection) {
            if (! $collection instanceof Collection) {
                continue;
            }
            foreach ($collection as $tr) {
                $urls[] = $tr->glossaryUrl(absolute: true);
            }
        }
        if ($supersededBy !== null) {
            $urls[] = $supersededBy->glossaryUrl(absolute: true);
        }
        foreach ($domainPeers as $tr) {
            $urls[] = $tr->glossaryUrl(absolute: true);
        }

        $urls = array_values(array_unique(array_filter($urls)));

        return array_slice($urls, 0, $max);
    }
}
