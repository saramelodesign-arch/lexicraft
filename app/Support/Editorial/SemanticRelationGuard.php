<?php

namespace App\Support\Editorial;

use App\Models\Concept;
use App\Models\ConceptRelation;
use Illuminate\Validation\ValidationException;

final class SemanticRelationGuard
{
    public static function assertCanCreate(int $conceptId, int $relatedConceptId, string $relationType): void
    {
        self::assertNoConflicts($conceptId, $relatedConceptId, $relationType);

        if (in_array($relationType, ['broader', 'narrower'], true) && self::introducesTaxonomyCycle($conceptId, $relatedConceptId, $relationType)) {
            throw ValidationException::withMessages([
                'relation_type' => __('admin.msg_semantic_cycle'),
            ]);
        }
    }

    public static function ensureSynonymIsSymmetric(int $conceptId, int $relatedConceptId): void
    {
        $exists = ConceptRelation::query()
            ->where('concept_id', $relatedConceptId)
            ->where('related_concept_id', $conceptId)
            ->where('relation_type', 'synonym')
            ->exists();

        if ($exists) {
            return;
        }

        ConceptRelation::query()->create([
            'concept_id' => $relatedConceptId,
            'related_concept_id' => $conceptId,
            'relation_type' => 'synonym',
        ]);
    }

    private static function introducesTaxonomyCycle(int $conceptId, int $relatedConceptId, string $relationType): bool
    {
        if ($relationType === 'broader') {
            return self::hasBroaderPath($relatedConceptId, $conceptId);
        }

        // concept -> narrower -> related is equivalent to related -> broader -> concept.
        return self::hasBroaderPath($conceptId, $relatedConceptId);
    }

    private static function assertNoConflicts(int $conceptId, int $relatedConceptId, string $relationType): void
    {
        if (self::relationExists($conceptId, $relatedConceptId, $relationType)) {
            throw ValidationException::withMessages([
                'relation_type' => __('admin.msg_relation_exists'),
            ]);
        }

        if (in_array($relationType, ['related', 'industry_variant'], true) && self::relationExists($relatedConceptId, $conceptId, $relationType)) {
            throw ValidationException::withMessages([
                'relation_type' => __('admin.msg_relation_exists'),
            ]);
        }

        if ($relationType === 'broader') {
            if (self::relationExists($conceptId, $relatedConceptId, 'narrower') || self::relationExists($relatedConceptId, $conceptId, 'broader')) {
                throw ValidationException::withMessages([
                    'relation_type' => __('admin.msg_semantic_cycle'),
                ]);
            }
        }

        if ($relationType === 'narrower') {
            if (self::relationExists($conceptId, $relatedConceptId, 'broader') || self::relationExists($relatedConceptId, $conceptId, 'narrower')) {
                throw ValidationException::withMessages([
                    'relation_type' => __('admin.msg_semantic_cycle'),
                ]);
            }
        }
    }

    private static function relationExists(int $fromId, int $toId, string $type): bool
    {
        return ConceptRelation::query()
            ->where('concept_id', $fromId)
            ->where('related_concept_id', $toId)
            ->where('relation_type', $type)
            ->exists();
    }

    private static function hasBroaderPath(int $startConceptId, int $targetConceptId): bool
    {
        $visited = [];
        $queue = [$startConceptId];

        while ($queue !== []) {
            $current = array_shift($queue);
            if (! is_int($current) || isset($visited[$current])) {
                continue;
            }

            if ($current === $targetConceptId) {
                return true;
            }

            $visited[$current] = true;

            $nextIds = ConceptRelation::query()
                ->where('concept_id', $current)
                ->where('relation_type', 'broader')
                ->pluck('related_concept_id')
                ->all();

            foreach ($nextIds as $nextId) {
                if (is_int($nextId) && ! isset($visited[$nextId])) {
                    $queue[] = $nextId;
                }
            }
        }

        return false;
    }

    public static function semanticWarningsForConcept(Concept $concept): array
    {
        $warnings = [];

        $relationCount = $concept->outgoingRelations()->count() + $concept->incomingRelations()->count();
        if ($relationCount === 0 && $concept->status === WorkflowStatus::PUBLISHED) {
            $warnings[] = __('admin.msg_published_no_semantic_relations');
        }

        $duplicateEdges = ConceptRelation::query()
            ->where('concept_id', $concept->id)
            ->selectRaw('related_concept_id, COUNT(*) as edge_count')
            ->groupBy('related_concept_id')
            ->havingRaw('COUNT(*) > 2')
            ->count();
        if ($duplicateEdges > 0) {
            $warnings[] = 'This concept has dense repeated edges to one or more targets; review relation clarity.';
        }

        return $warnings;
    }
}
