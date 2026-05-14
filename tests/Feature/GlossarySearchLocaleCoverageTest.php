<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GlossarySearchLocaleCoverageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return iterable<string, array{0: string, 1: string, 2: string}>
     */
    public static function localeTerms(): iterable
    {
        yield 'en' => ['en', 'UniqueENmarker', 'Glossary EN'];
        yield 'pt' => ['pt', 'UniquePTmarker', 'Glossary PT'];
        yield 'fr' => ['fr', 'UniqueFRmarker', 'Glossary FR'];
        yield 'de' => ['de', 'UniqueDEmarker', 'Glossary DE'];
        yield 'it' => ['it', 'UniqueITmarker', 'Glossary IT'];
        yield 'es' => ['es', 'UniqueESmarker', 'Glossary ES'];
    }

    #[Test]
    #[DataProvider('localeTerms')]
    public function search_results_stay_within_locale(string $locale, string $query, string $term): void
    {
        $this->seedAllSixLanguages();

        $concept = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        $markers = [
            'en' => 'UniqueENmarker',
            'pt' => 'UniquePTmarker',
            'fr' => 'UniqueFRmarker',
            'de' => 'UniqueDEmarker',
            'it' => 'UniqueITmarker',
            'es' => 'UniqueESmarker',
        ];

        foreach (['en', 'pt', 'fr', 'de', 'it', 'es'] as $code) {
            $lang = Language::query()->where('code', $code)->firstOrFail();
            ConceptTranslation::query()->create([
                'concept_id' => $concept->id,
                'language_id' => $lang->id,
                'term' => 'Glossary '.strtoupper($code),
                'slug' => 'glossary-'.$code,
                'short_definition' => 'Definition '.$markers[$code].' only.',
                'full_definition' => null,
                'seo_title' => null,
                'seo_description' => null,
                'meta_keywords' => null,
                'industry_notes' => null,
            ]);
        }

        $response = $this->get(route('search', ['locale' => $locale, 'q' => $query]));
        $response->assertOk();
        $response->assertSee($term, false);
        foreach ($markers as $code => $marker) {
            if ($code === $locale) {
                continue;
            }
            $response->assertDontSee($marker, false);
        }
    }

    private function seedAllSixLanguages(): void
    {
        $defs = [
            ['en', 'English', 'English'],
            ['pt', 'Portuguese', 'Português'],
            ['fr', 'French', 'Français'],
            ['de', 'German', 'Deutsch'],
            ['it', 'Italian', 'Italiano'],
            ['es', 'Spanish', 'Español'],
        ];
        foreach ($defs as [$code, $name, $native]) {
            Language::query()->create([
                'code' => $code,
                'name' => $name,
                'native_name' => $native,
                'flag_icon' => null,
                'is_active' => true,
            ]);
        }
    }
}
