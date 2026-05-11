<?php

namespace Database\Factories;

use App\Models\Concept;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Concept>
 */
class ConceptFactory extends Factory
{
    protected $model = Concept::class;

    public function definition(): array
    {
        return [
            'status' => 'draft',
            'difficulty_level' => null,
            'is_featured' => false,
        ];
    }
}
