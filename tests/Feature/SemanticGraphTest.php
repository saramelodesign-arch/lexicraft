<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\ConceptRelation;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Support\Editorial\WorkflowStatus;
use App\Support\SemanticGraph;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SemanticGraphTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function self_relation_is_rejected(): void
    {
        $en = $this->makeLanguage('en');
        $concept = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        $this->expectException(ValidationException::class);

        ConceptRelation::query()->create([
            'concept_id' => $concept->id,
            'related_concept_id' => $concept->id,
            'relation_type' => 'related',
        ]);
    }

    #[Test]
    public function invalid_relation_type_is_rejected(): void
    {
        $en = $this->makeLanguage('en');
        $a = Concept::query()->create(['status' => 'published', 'difficulty_level' => null, 'is_featured' => false]);
        $b = Concept::query()->create(['status' => 'published', 'difficulty_level' => null, 'is_featured' => false]);

        $this->expectException(ValidationException::class);

        ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'invalid_type',
        ]);
    }

    #[Test]
    public function duplicate_relation_row_is_prevented_by_unique_index(): void
    {
        $en = $this->makeLanguage('en');
        $a = Concept::query()->create(['status' => 'published', 'difficulty_level' => null, 'is_featured' => false]);
        $b = Concept::query()->create(['status' => 'published', 'difficulty_level' => null, 'is_featured' => false]);

        ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'related',
        ]);

        $this->expectException(QueryException::class);

        ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'related',
        ]);
    }

    #[Test]
    public function bidirectional_broader_lists_peer_on_both_concepts(): void
    {
        $en = $this->makeLanguage('en');
        $narrow = Concept::query()->create(['status' => 'published', 'difficulty_level' => null, 'is_featured' => false]);
        $broad = Concept::query()->create(['status' => 'published', 'difficulty_level' => null, 'is_featured' => false]);

        ConceptTranslation::query()->create([
            'concept_id' => $narrow->id,
            'language_id' => $en->id,
            'term' => 'Narrow term',
            'slug' => 'narrow-term',
            'short_definition' => null,
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $broad->id,
            'language_id' => $en->id,
            'term' => 'Broad family',
            'slug' => 'broad-family',
            'short_definition' => null,
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
        ]);

        ConceptRelation::query()->create([
            'concept_id' => $narrow->id,
            'related_concept_id' => $broad->id,
            'relation_type' => 'broader',
        ]);

        $narrow->load([
            'outgoingRelations.relatedConcept.translations' => fn ($q) => $q->where('language_id', $en->id),
            'incomingRelations.concept.translations' => fn ($q) => $q->where('language_id', $en->id),
        ]);
        $broad->load([
            'outgoingRelations.relatedConcept.translations' => fn ($q) => $q->where('language_id', $en->id),
            'incomingRelations.concept.translations' => fn ($q) => $q->where('language_id', $en->id),
        ]);

        $gNarrow = SemanticGraph::peerTranslationsGrouped($narrow, $en->id);
        $gBroad = SemanticGraph::peerTranslationsGrouped($broad, $en->id);

        $this->assertCount(1, $gNarrow['broader']);
        $this->assertSame('Broad family', $gNarrow['broader']->first()->term);

        $this->assertCount(1, $gBroad['narrower']);
        $this->assertSame('Narrow term', $gBroad['narrower']->first()->term);
    }

    #[Test]
    public function concept_page_renders_semantic_sections_and_see_also_json_ld(): void
    {
        $en = $this->makeLanguage('en');
        $a = Concept::query()->create(['status' => 'published', 'difficulty_level' => null, 'is_featured' => false]);
        $b = Concept::query()->create(['status' => 'published', 'difficulty_level' => null, 'is_featured' => false]);

        ConceptTranslation::query()->create([
            'concept_id' => $a->id,
            'language_id' => $en->id,
            'term' => 'Lasting',
            'slug' => 'lasting',
            'short_definition' => 'Alpha',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'status' => WorkflowStatus::PUBLISHED,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $b->id,
            'language_id' => $en->id,
            'term' => 'Upper',
            'slug' => 'upper',
            'short_definition' => 'Beta',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'status' => WorkflowStatus::PUBLISHED,
        ]);

        ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'related',
        ]);

        $this->get(route('glossary.concept', ['locale' => 'en', 'slug' => 'lasting']))
            ->assertOk()
            ->assertSee('Process workflow', false)
            ->assertSee('Downstream (after)', false)
            ->assertSee('Upper', false)
            ->assertSee('Explore related terminology', false)
            ->assertSee('seeAlso', false);
    }

    #[Test]
    public function synonym_edges_are_deduplicated_when_bidirectional(): void
    {
        $en = $this->makeLanguage('en');
        $a = Concept::query()->create(['status' => 'published', 'difficulty_level' => null, 'is_featured' => false]);
        $b = Concept::query()->create(['status' => 'published', 'difficulty_level' => null, 'is_featured' => false]);

        foreach ([$a, $b] as $concept) {
            ConceptTranslation::query()->create([
                'concept_id' => $concept->id,
                'language_id' => $en->id,
                'term' => $concept->id === $a->id ? 'Clicking' : 'Die cutting',
                'slug' => $concept->id === $a->id ? 'clicking' : 'die-cutting',
                'short_definition' => null,
                'full_definition' => null,
                'seo_title' => null,
                'seo_description' => null,
                'meta_keywords' => null,
                'industry_notes' => null,
            ]);
        }

        ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'synonym',
        ]);
        ConceptRelation::query()->create([
            'concept_id' => $b->id,
            'related_concept_id' => $a->id,
            'relation_type' => 'synonym',
        ]);

        $a->load([
            'outgoingRelations.relatedConcept.translations' => fn ($q) => $q->where('language_id', $en->id),
            'incomingRelations.concept.translations' => fn ($q) => $q->where('language_id', $en->id),
        ]);

        $g = SemanticGraph::peerTranslationsGrouped($a, $en->id);
        $this->assertCount(1, $g['synonyms']);
    }

    private function makeLanguage(string $code): Language
    {
        return Language::query()->create([
            'code' => $code,
            'name' => $code,
            'native_name' => $code,
            'flag_icon' => null,
            'is_active' => true,
        ]);
    }
}
