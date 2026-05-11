<?php

namespace Database\Factories;

use App\Models\ConceptTranslation;
use App\Models\Example;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Example>
 */
class ExampleFactory extends Factory
{
    protected $model = Example::class;

    public function definition(): array
    {
        return [
            'concept_translation_id' => ConceptTranslation::factory(),
            'example' => fake()->sentence(),
            'context' => fake()->optional()->randomElement(['production', 'meeting', 'quality_control', 'retail', 'technical_design']),
            'sort_order' => 0,
        ];
    }
}
