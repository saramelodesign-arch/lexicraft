<?php

namespace Database\Factories;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ConceptTranslation>
 */
class ConceptTranslationFactory extends Factory
{
    protected $model = ConceptTranslation::class;

    public function definition(): array
    {
        $term = fake()->unique()->words(3, true);

        return [
            'concept_id' => Concept::factory(),
            'language_id' => Language::factory(),
            'status' => 'draft',
            'term' => $term,
            'slug' => Str::slug($term).'-'.fake()->unique()->numerify('####'),
            'short_definition' => fake()->optional()->sentence(),
            'full_definition' => fake()->optional()->paragraphs(2, true),
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'seo_canonical_url' => null,
            'og_title' => null,
            'og_description' => null,
        ];
    }
}
