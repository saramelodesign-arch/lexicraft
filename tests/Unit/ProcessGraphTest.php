<?php

namespace Tests\Unit;

use App\Models\Concept;
use App\Models\ConceptRelation;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Support\ProcessGraph;
use App\Support\SemanticGraph;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProcessGraphTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function asymmetric_related_edges_surface_as_upstream_and_downstream(): void
    {
        $en = $this->makeLanguage('en');
        $a = $this->makeConcept('Cementing', 'cementing', $en);
        $b = $this->makeConcept('Heat activation', 'heat-activation', $en);
        $c = $this->makeConcept('Sole pressing', 'sole-pressing', $en);

        ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'related',
        ]);
        ConceptRelation::query()->create([
            'concept_id' => $b->id,
            'related_concept_id' => $c->id,
            'relation_type' => 'related',
        ]);

        $this->loadRelations($b);

        $w = ProcessGraph::workflowNeighbors($b, $en->id);

        $this->assertSame('Cementing', $w['upstream']->first()?->term);
        $this->assertSame('Sole pressing', $w['downstream']->first()?->term);
        $this->assertCount(0, $w['route_alternatives']);
    }

    #[Test]
    public function process_directional_peers_are_excluded_from_flat_related_bucket(): void
    {
        $en = $this->makeLanguage('en');
        $a = $this->makeConcept('Lasting', 'lasting', $en);
        $b = $this->makeConcept('Upper', 'upper', $en);

        ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'related',
        ]);

        $this->loadRelations($a);

        $grouped = SemanticGraph::peerTranslationsGrouped($a, $en->id);

        $this->assertCount(0, $grouped['related']);
        $this->assertSame('Upper', ProcessGraph::workflowNeighbors($a, $en->id)['downstream']->first()?->term);
    }

    #[Test]
    public function bidirectional_route_concepts_surface_as_route_alternatives(): void
    {
        $en = $this->makeLanguage('en');
        $a = $this->makeConcept('Goodyear welt', 'goodyear-welt', $en);
        $b = $this->makeConcept('Blake stitch', 'blake-stitch', $en);

        ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'related',
        ]);
        ConceptRelation::query()->create([
            'concept_id' => $b->id,
            'related_concept_id' => $a->id,
            'relation_type' => 'related',
        ]);

        $this->loadRelations($a);

        $w = ProcessGraph::workflowNeighbors($a, $en->id);

        $this->assertCount(1, $w['route_alternatives']);
        $this->assertSame('Blake stitch', $w['route_alternatives']->first()?->term);
        $this->assertCount(0, $w['upstream']);
        $this->assertCount(0, $w['downstream']);
    }

    #[Test]
    public function concept_page_renders_workflow_navigation_for_process_chain(): void
    {
        $en = $this->makeLanguage('en');
        $a = $this->makeConcept('Cementing', 'cementing', $en);
        $b = $this->makeConcept('Heat activation', 'heat-activation', $en);

        ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'related',
        ]);

        $this->get(route('glossary.concept', ['locale' => 'en', 'slug' => 'heat-activation']))
            ->assertOk()
            ->assertSee('Process workflow', false)
            ->assertSee('Upstream (before)', false)
            ->assertSee('Cementing', false)
            ->assertSee('id="semantic-workflow"', false);
    }

    #[Test]
    public function contextual_guidance_phrases_use_locale_templates(): void
    {
        $de = $this->makeLanguage('de');
        $a = $this->makeConcept('Verklebung', 'cementing', $de);
        $b = $this->makeConcept('Waermeaktivierung', 'heat-activation', $de);
        $c = $this->makeConcept('Sohlenpressung', 'sole-pressing', $de);

        ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'related',
        ]);
        ConceptRelation::query()->create([
            'concept_id' => $b->id,
            'related_concept_id' => $c->id,
            'relation_type' => 'related',
        ]);

        $this->loadRelations($b);

        $phrases = ProcessGraph::contextualGuidancePhrases($b, $de->id);

        $this->assertContains(
            'Waermeaktivierung in der Fertigungsfolge nach Verklebung, vor Sohlenpressung',
            $phrases,
        );
    }

    #[Test]
    public function second_order_welt_prep_slug_receives_line_position_guidance_phrase(): void
    {
        $en = $this->makeLanguage('en');
        $channeling = $this->makeConcept('Channeling', 'channeling', $en);
        $gemming = $this->makeConcept('Gemming rib', 'gemming-rib', $en);
        $holdfast = $this->makeConcept('Holdfast stitch', 'holdfast-stitch', $en);

        ConceptRelation::query()->create([
            'concept_id' => $channeling->id,
            'related_concept_id' => $gemming->id,
            'relation_type' => 'related',
        ]);
        ConceptRelation::query()->create([
            'concept_id' => $gemming->id,
            'related_concept_id' => $holdfast->id,
            'relation_type' => 'related',
        ]);

        $this->loadRelations($gemming);

        $phrases = ProcessGraph::contextualGuidancePhrases($gemming, $en->id);

        $this->assertContains(
            'Gemming rib after Channeling, before Holdfast stitch',
            $phrases,
        );
        $this->assertLessThanOrEqual(3, count($phrases));
        $this->assertNotContains('Gemming rib follows Channeling', $phrases);
    }

    #[Test]
    public function related_stage_labels_prioritize_concept_home_workflow_family(): void
    {
        $de = $this->makeLanguage('de');
        $gemming = $this->makeConcept('Gemming-Rippe', 'gemming-rib', $de);
        $this->makeConcept('Nahtkanalierung', 'channeling', $de);
        $this->makeConcept('Holdfast-Stich', 'holdfast-stitch', $de);

        $this->loadRelations($gemming);

        $signals = ProcessGraph::continuitySignals($gemming, $de->id);

        $this->assertCount(1, $signals['related_stages']);
        $this->assertSame('Rahmenkonstruktion', $signals['related_stages'][0]);
    }

    #[Test]
    public function related_stage_labels_are_localized_for_display_and_search(): void
    {
        $de = $this->makeLanguage('de');
        $roughing = $this->makeConcept('Aufrauen', 'roughing', $de);
        $this->makeConcept('Primeraer', 'primer-coat', $de);
        $this->makeConcept('Verklebung', 'cementing', $de);

        ConceptRelation::query()->create([
            'concept_id' => $roughing->id,
            'related_concept_id' => ConceptTranslation::query()->where('slug', 'primer-coat')->first()->concept_id,
            'relation_type' => 'related',
        ]);

        $this->loadRelations($roughing);

        $signals = ProcessGraph::continuitySignals($roughing, $de->id);

        $this->assertContains('geklebte Konstruktion', $signals['related_stages']);
        $this->assertNotContains('cemented construction', $signals['related_stages']);
    }

    #[Test]
    public function semantic_reading_journeys_include_relevant_lasting_path(): void
    {
        $en = $this->makeLanguage('en');
        $lasting = $this->makeConcept('Lasting', 'lasting', $en);
        $this->makeConcept('Shoe last', 'shoe-last', $en);
        $this->makeConcept('Upper', 'upper', $en);
        $this->makeConcept('Toe lasting', 'toe-lasting', $en);
        $this->makeConcept('Side lasting', 'side-lasting', $en);
        $this->makeConcept('Back-part lasting', 'back-part-lasting', $en);
        $this->makeConcept('Seat lasting', 'seat-lasting', $en);

        $this->loadRelations($lasting);

        $journeys = ProcessGraph::semanticReadingJourneys($lasting, $en->id, max: 6);
        $keys = $journeys->pluck('key')->all();

        $this->assertContains('journey_understanding_lasting', $keys);
        $lastingJourney = $journeys->firstWhere('key', 'journey_understanding_lasting');
        $this->assertNotNull($lastingJourney);
        $this->assertGreaterThanOrEqual(3, $lastingJourney['items']->count());
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

    private function makeConcept(string $term, string $slug, Language $lang): Concept
    {
        $concept = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $lang->id,
            'term' => $term,
            'slug' => $slug,
            'short_definition' => 'Def',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'status' => 'published',
        ]);

        return $concept;
    }

    private function loadRelations(Concept $concept): void
    {
        $langId = $concept->translations->first()->language_id;
        $concept->load([
            'outgoingRelations.relatedConcept.translations' => fn ($q) => $q->where('language_id', $langId),
            'incomingRelations.concept.translations' => fn ($q) => $q->where('language_id', $langId),
        ]);
    }
}
