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
        return [
            'concept_id' => Concept::factory(),
            'related_concept_id' => Concept::factory(),
            'relation_type' => fake()->randomElement(['synonym', 'related', 'broader', 'narrower', 'deprecated']),
        ];
    }
}
