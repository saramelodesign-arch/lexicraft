<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\ConceptRelation;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Models\DomainTranslation;
use App\Models\Language;
use App\Support\Locales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MultilingualSearchTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function search_results_page_lists_matches_in_locale_only(): void
    {
        [$en, $pt] = $this->seedLanguages();

        $concept = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $en->id,
            'term' => 'Lasting',
            'slug' => 'lasting',
            'short_definition' => 'Pulling the upper onto the last.',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $pt->id,
            'term' => 'Moldação',
            'slug' => 'moldacao',
            'short_definition' => 'Esticar o cabedal na forma.',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $this->get(route('search', ['locale' => 'en', 'q' => 'lasting']))
            ->assertOk()
            ->assertSee('Lasting', false)
            ->assertSee('Pulling the upper onto the last.', false)
            ->assertDontSee('Moldação', false);

        $this->get(route('search', ['locale' => 'pt', 'q' => 'mold']))
            ->assertOk()
            ->assertSee('/pt/glossary/moldacao', false)
            ->assertSee('Esticar o cabedal na forma.', false)
            ->assertDontSee('Lasting', false);
    }

    #[Test]
    public function search_finds_by_domain_translation_in_same_language(): void
    {
        [$en] = $this->seedLanguages();

        $domain = Domain::query()->create([
            'parent_id' => null,
            'slug' => 'edge-work',
            'icon' => null,
            'sort_order' => 0,
            'is_active' => true,
        ]);

        DomainTranslation::query()->create([
            'domain_id' => $domain->id,
            'language_id' => $en->id,
            'name' => 'Edge finishing operations',
            'slug' => 'edge-work',
            'description' => null,
        ]);

        $concept = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        $concept->domains()->attach($domain->id);

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $en->id,
            'term' => 'Burnishing',
            'slug' => 'burnishing',
            'short_definition' => 'Polishing the edge.',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $this->get(route('search', ['locale' => 'en', 'q' => 'finishing']))
            ->assertOk()
            ->assertSee('Burnishing', false)
            ->assertSee('Edge finishing operations', false);
    }

    #[Test]
    public function search_finds_via_related_concept_term(): void
    {
        [$en] = $this->seedLanguages();

        $conceptA = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        $conceptB = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        ConceptRelation::query()->create([
            'concept_id' => $conceptA->id,
            'related_concept_id' => $conceptB->id,
            'relation_type' => 'related',
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $conceptA->id,
            'language_id' => $en->id,
            'term' => 'Parent op',
            'slug' => 'parent-op',
            'short_definition' => 'See related.',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $conceptB->id,
            'language_id' => $en->id,
            'term' => 'Related widget',
            'slug' => 'related-widget',
            'short_definition' => 'The actual widget.',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $this->get(route('search', ['locale' => 'en', 'q' => 'widget']))
            ->assertOk()
            ->assertSee('Parent op', false);
    }

    #[Test]
    public function localized_url_preserves_search_query_string(): void
    {
        $this->seedLanguages();

        $this->get(route('search', ['locale' => 'en', 'q' => 'lasting']));

        $ptUrl = Locales::localizedUrl('pt');

        $this->assertStringContainsString('q=lasting', $ptUrl);
        $this->assertMatchesRegularExpression('#/pt/search(\?|$)#', $ptUrl);
    }

    /**
     * @return array{0: Language, 1: Language}
     */
    private function seedLanguages(): array
    {
        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $pt = Language::query()->create([
            'code' => 'pt',
            'name' => 'Portuguese',
            'native_name' => 'Português',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        return [$en, $pt];
    }
}
