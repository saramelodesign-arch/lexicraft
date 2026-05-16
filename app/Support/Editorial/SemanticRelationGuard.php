<?php

namespace App\Support\Editorial;

use App\Models\Concept;
use App\Models\ConceptRelation;
use Illuminate\Validation\ValidationException;

final class SemanticRelationGuard
{
    public static function assertCanCreate(int $conceptId, int $relatedConceptId, string $relationType): void
    {
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

        return $warnings;
    }
}
