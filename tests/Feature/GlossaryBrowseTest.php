<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Models\DomainTranslation;
use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GlossaryBrowseTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function glossary_lists_terms_for_locale_and_starting_letter(): void
    {
        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $domain = Domain::query()->create([
            'parent_id' => null,
            'slug' => 'footwear',
            'icon' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        DomainTranslation::query()->create([
            'domain_id' => $domain->id,
            'language_id' => $en->id,
            'name' => 'Footwear',
            'slug' => 'footwear',
            'description' => 'Test domain',
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
            'term' => 'Anvil press dwell',
            'slug' => 'anvil-press-dwell',
            'short_definition' => 'Controlled time under heat platens.',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $response = $this->get(route('glossary.letter', ['locale' => 'en', 'letter' => 'a']));

        $response->assertOk();
        $response->assertSeeLivewire('glossary.browse');
        $response->assertSee('Anvil press dwell', false);
        $response->assertSee('Footwear', false);
        $response->assertSee(route('glossary.letter', ['locale' => 'en', 'letter' => 'b'], false), false);
    }

    #[Test]
    public function glossary_filters_by_first_letter_of_translated_term_per_locale(): void
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

        $concept = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $en->id,
            'term' => 'Zebra specification (EN)',
            'slug' => 'zebra-specification-en',
            'short_definition' => null,
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $pt->id,
            'term' => 'Zebra em português',
            'slug' => 'zebra-em-portugues',
            'short_definition' => null,
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $this->get(route('glossary.letter', ['locale' => 'en', 'letter' => 'z']))
            ->assertOk()
            ->assertSee('Zebra specification (EN)', false);

        $this->get(route('glossary.letter', ['locale' => 'pt', 'letter' => 'z']))
            ->assertOk()
            ->assertSee('Zebra em português', false)
            ->assertDontSee('Zebra specification (EN)', false);
    }

    #[Test]
    public function glossary_paginates_terms(): void
    {
        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        for ($i = 1; $i <= 18; $i++) {
            $concept = Concept::query()->create([
                'status' => 'published',
                'difficulty_level' => null,
                'is_featured' => false,
            ]);

            ConceptTranslation::query()->create([
                'concept_id' => $concept->id,
                'language_id' => $en->id,
                'term' => sprintf('Bench term %02d', $i),
                'slug' => 'bench-term-'.sprintf('%02d', $i),
                'short_definition' => 'Short',
                'full_definition' => null,
                'seo_title' => null,
                'seo_description' => null,
                'meta_keywords' => null,
                'industry_notes' => null,
            ]);
        }

        $page1 = $this->get(route('glossary.letter', ['locale' => 'en', 'letter' => 'b']));
        $page1->assertOk();
        $page1->assertSee('Bench term 01', false);
        $page1->assertDontSee('Bench term 18', false);

        $page2 = $this->get(route('glossary.letter', ['locale' => 'en', 'letter' => 'b']).'?page=2');
        $page2->assertOk();
        $page2->assertSee('Bench term 18', false);
    }

    #[Test]
    public function draft_concepts_are_excluded_from_glossary(): void
    {
        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $draft = Concept::query()->create([
            'status' => 'draft',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $draft->id,
            'language_id' => $en->id,
            'term' => 'Draft alpha',
            'slug' => 'draft-alpha',
            'short_definition' => null,
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $this->get(route('glossary.letter', ['locale' => 'en', 'letter' => 'a']))
            ->assertOk()
            ->assertDontSee('Draft alpha', false);
    }
}
