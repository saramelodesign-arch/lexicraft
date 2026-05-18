<?php

namespace Tests\Unit;

use App\Models\Concept;
use App\Models\ConceptRelation;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Models\DomainTranslation;
use App\Models\Language;
use App\Support\ConceptSearchDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ConceptSearchDocumentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_builds_stable_meilisearch_shaped_document(): void
    {
        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $domain = Domain::query()->create([
            'slug' => 'footwear',
            'is_active' => true,
            'parent_id' => null,
            'sort_order' => 0,
        ]);

        DomainTranslation::query()->create([
            'domain_id' => $domain->id,
            'language_id' => $en->id,
            'name' => 'Footwear',
            'description' => 'Shoes and boots',
            'slug' => 'footwear',
        ]);

        $a = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        $b = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        $a->domains()->sync([$domain->id]);

        ConceptRelation::query()->create([
            'concept_id' => $b->id,
            'related_concept_id' => $a->id,
            'relation_type' => 'related',
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $b->id,
            'language_id' => $en->id,
            'term' => 'Related term',
            'slug' => 'related-term',
            'short_definition' => null,
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        $tr = ConceptTranslation::query()->create([
            'concept_id' => $a->id,
            'language_id' => $en->id,
            'term' => 'Lasting',
            'slug' => 'lasting',
            'short_definition' => 'On the last.',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => 'upper lasting, shoe last',
            'industry_notes' => null,
        ]);

        $doc = ConceptSearchDocument::fromTranslation($tr->fresh(['language', 'concept']));

        $this->assertSame('en', $doc['language_code']);
        $this->assertSame('lasting', $doc['slug']);
        $this->assertSame('published', $doc['status']);
        $this->assertTrue($doc['is_published']);
        $this->assertContains('upper lasting', $doc['synonyms']);
        $this->assertSame([], $doc['relation_types']);
        $this->assertContains('footwear', $doc['domain_slugs']);
        $this->assertContains('Footwear', $doc['domain_names']);
        $this->assertContains('Related term', $doc['related_terms']);
        $this->assertContains('Related term', $doc['workflow_upstream_terms']);
        $this->assertArrayHasKey('workflow_route_terms', $doc);
        $this->assertArrayHasKey('workflow_journey_terms', $doc);
        $this->assertArrayHasKey('workflow_context_phrases', $doc);
        $this->assertIsArray($doc['workflow_route_terms']);
        $this->assertIsArray($doc['workflow_journey_terms']);
        $this->assertIsArray($doc['workflow_context_phrases']);
        $this->assertNotEmpty($doc['workflow_context_phrases']);
        $this->assertStringContainsString('lasting', $doc['searchable_text']);
        $this->assertStringContainsString('related term', $doc['searchable_text']);
    }
}
