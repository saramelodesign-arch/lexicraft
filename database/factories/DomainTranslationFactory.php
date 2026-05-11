<?php

namespace Database\Factories;

use App\Models\Domain;
use App\Models\DomainTranslation;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DomainTranslation>
 */
class DomainTranslationFactory extends Factory
{
    protected $model = DomainTranslation::class;

    public function definition(): array
    {
        return [
            'domain_id' => Domain::factory(),
            'language_id' => Language::factory(),
            'name' => fake()->words(4, true),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
