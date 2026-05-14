<?php

namespace Database\Factories;

use App\Models\Domain;
use App\Models\DomainTranslation;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DomainTranslation>
 */
class DomainTranslationFactory extends Factory
{
    protected $model = DomainTranslation::class;

    public function definition(): array
    {
        $name = fake()->words(4, true);

        return [
            'domain_id' => Domain::factory(),
            'language_id' => Language::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('####'),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
