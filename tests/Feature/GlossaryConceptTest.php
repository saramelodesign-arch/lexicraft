<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Support\Locales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GlossaryConceptTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function concept_page_renders_by_locale_slug_with_seo_and_hreflang(): void
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
            'term' => 'Lasting',
            'slug' => 'lasting',
            'short_definition' => 'Pulling the upper onto the last.',
            'full_definition' => "Full body of the definition.\nSecond line.",
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

        $enUrl = route('glossary.concept', ['locale' => 'en', 'slug' => 'lasting'], absolute: true);
        $ptUrl = route('glossary.concept', ['locale' => 'pt', 'slug' => 'moldacao'], absolute: true);

        $r = $this->get(route('glossary.concept', ['locale' => 'en', 'slug' => 'lasting']));
        $r->assertOk();
        $r->assertSee('Lasting', false);
        $r->assertSee('Pulling the upper onto the last.', false);
        $r->assertSee($enUrl, false);
        $r->assertSee('hreflang="en"', false);
        $r->assertSee('hreflang="pt"', false);
        $r->assertSee('hreflang="x-default"', false);
        $r->assertSee($ptUrl, false);
        $r->assertSee('https://schema.org', false);
        $r->assertSee('DefinedTerm', false);
        $r->assertSee('"name":"LexiCraft Glossary"', false);
        $r->assertSee('BreadcrumbList', false);
    }

    #[Test]
    public function concept_page_respects_editorial_open_graph_and_canonical_override(): void
    {
        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
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
            'term' => 'Bonding',
            'slug' => 'bonding',
            'short_definition' => 'Adhesive assembly step.',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'seo_canonical_url' => 'https://example.test/en/glossary/bonding-canonical',
            'og_title' => 'OG bonding headline',
            'og_description' => 'OG bonding summary for social cards.',
        ]);

        $r = $this->get(route('glossary.concept', ['locale' => 'en', 'slug' => 'bonding']));
        $r->assertOk();
        $r->assertSee('rel="canonical" href="https://example.test/en/glossary/bonding-canonical"', false);
        $r->assertSee('property="og:title" content="OG bonding headline"', false);
        $r->assertSee('property="og:description" content="OG bonding summary for social cards."', false);
    }

    #[Test]
    public function draft_concept_returns_404_on_concept_route(): void
    {
        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $concept = Concept::query()->create([
            'status' => 'draft',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $en->id,
            'term' => 'Hidden',
            'slug' => 'hidden-term',
            'short_definition' => null,
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $this->get(route('glossary.concept', ['locale' => 'en', 'slug' => 'hidden-term']))
            ->assertNotFound();
    }

    #[Test]
    public function localized_url_maps_glossary_concept_slug_to_target_locale(): void
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
            'term' => 'Skiving',
            'slug' => 'skiving',
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
            'term' => 'Rebaixamento',
            'slug' => 'rebaixamento',
            'short_definition' => null,
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $this->get(route('glossary.concept', ['locale' => 'en', 'slug' => 'skiving']));

        $this->assertSame(
            route('glossary.concept', ['locale' => 'pt', 'slug' => 'rebaixamento'], false),
            Locales::localizedUrl('pt'),
        );
    }
}
