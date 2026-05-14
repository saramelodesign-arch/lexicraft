<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizQuestion>
 */
class QuizQuestionFactory extends Factory
{
    protected $model = QuizQuestion::class;

    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'type' => 'multiple_choice',
            'payload' => [
                'prompt' => 'Sample?',
                'choices' => [
                    ['key' => 'a', 'label' => 'One'],
                    ['key' => 'b', 'label' => 'Two'],
                ],
                'correct' => 'a',
            ],
            'sort_order' => 0,
        ];
    }
}
