<?php

namespace Database\Factories;

use App\Models\Concept;
use App\Models\ConceptRelation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConceptRelation>
 */
class ConceptRelationFactory extends Factory
{
    protected $model = ConceptRelation::class;

    public function definition(): array
    {
        $a = Concept::factory()->create();
        $b = Concept::factory()->create();

        return [
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => fake()->randomElement([
                'synonym', 'related', 'broader', 'narrower', 'deprecated', 'industry_variant',
            ]),
        ];
    }
}
