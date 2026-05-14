<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quiz>
 */
class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition(): array
    {
        return [
            'locale' => 'en',
            'slug' => fake()->unique()->slug(3),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'domain_id' => null,
            'is_published' => true,
        ];
    }
}
