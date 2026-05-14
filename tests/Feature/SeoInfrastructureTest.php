<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Models\DomainTranslation;
use App\Models\Language;
use App\Support\Seo\SeoUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SeoInfrastructureTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function robots_txt_points_to_sitemap(): void
    {
        $r = $this->get('/robots.txt');
        $r->assertOk();
        $r->assertSee('User-agent:', false);
        $r->assertSee('Sitemap: '.SeoUrl::sitemapIndex(), false);
        $r->assertSee('Disallow: /*/search', false);
    }

    #[Test]
    public function sitemap_index_lists_child_sitemaps(): void
    {
        Language::query()->create([
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

        $en = Language::query()->where('code', 'en')->firstOrFail();

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $en->id,
            'term' => 'T',
            'slug' => 't',
            'short_definition' => null,
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $r = $this->get('/sitemap.xml');
        $r->assertOk();
        $r->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $r->assertSee('<sitemapindex', false);
        $r->assertSee(route('sitemaps.static', ['locale' => 'en'], absolute: true), false);
        $r->assertSee(route('sitemaps.concepts', ['locale' => 'en', 'chunk' => 1], absolute: true), false);
        $r->assertSee(route('sitemaps.domains', ['locale' => 'en'], absolute: true), false);
    }

    #[Test]
    public function concept_sitemap_emits_concept_urls(): void
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
            'term' => 'Lasting',
            'slug' => 'lasting',
            'short_definition' => null,
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $url = route('glossary.concept', ['locale' => 'en', 'slug' => 'lasting'], absolute: true);
        $r = $this->get('/sitemaps/concepts-en-1.xml');
        $r->assertOk();
        $r->assertSee(htmlspecialchars($url, ENT_XML1 | ENT_COMPAT, 'UTF-8'), false);
    }

    #[Test]
    public function search_results_always_emit_noindex(): void
    {
        Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $r = $this->get(route('search', ['locale' => 'en']));
        $r->assertOk();
        $r->assertSee('name="robots" content="noindex,follow"', false);
    }

    #[Test]
    public function home_includes_hreflang_and_multiple_json_ld_blocks(): void
    {
        Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        Language::query()->create([
            'code' => 'pt',
            'name' => 'Portuguese',
            'native_name' => 'Português',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $r = $this->get(route('home', ['locale' => 'en']));
        $r->assertOk();
        $r->assertSee('hreflang="en"', false);
        $r->assertSee('hreflang="pt"', false);
        $r->assertSee('"@type":"Organization"', false);
        $r->assertSee('"@type":"WebSite"', false);
        $r->assertSee('"@type":"DefinedTermSet"', false);
    }

    #[Test]
    public function domain_sitemap_lists_domain_show_urls(): void
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
            'sort_order' => 0,
            'is_active' => true,
        ]);

        DomainTranslation::query()->create([
            'domain_id' => $domain->id,
            'language_id' => $en->id,
            'name' => 'Footwear',
            'slug' => 'footwear',
            'description' => null,
        ]);

        $url = route('domains.show', ['locale' => 'en', 'slug' => 'footwear'], absolute: true);
        $r = $this->get('/sitemaps/domains-en.xml');
        $r->assertOk();
        $r->assertSee(htmlspecialchars($url, ENT_XML1 | ENT_COMPAT, 'UTF-8'), false);
    }
}
