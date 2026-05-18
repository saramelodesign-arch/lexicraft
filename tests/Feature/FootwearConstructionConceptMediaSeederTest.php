<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\Language;
use App\Support\FootwearConstructionMediaAuthority;
use Database\Seeders\ConceptsSeeder;
use Database\Seeders\DomainsSeeder;
use Database\Seeders\FootwearConstructionConceptMediaSeeder;
use Database\Seeders\LanguagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FootwearConstructionConceptMediaSeederTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function seeder_attaches_workflow_and_cutaway_diagrams_to_priority_concepts(): void
    {
        $this->seed([
            LanguagesSeeder::class,
            DomainsSeeder::class,
            ConceptsSeeder::class,
            FootwearConstructionConceptMediaSeeder::class,
        ]);

        $enId = Language::query()->where('code', 'en')->value('id');
        $this->assertNotNull($enId);

        foreach (FootwearConstructionMediaAuthority::PRIORITY_CONCEPT_KEYS as $slug) {
            $concept = Concept::query()
                ->whereHas('translations', fn ($q) => $q->where('language_id', $enId)->where('slug', $slug))
                ->first();

            $this->assertNotNull($concept, "Missing concept for {$slug}");
            $this->assertGreaterThanOrEqual(2, $concept->media()->count(), "Expected seeded media for {$slug}");
        }
    }

    #[Test]
    public function lasting_concept_page_renders_workflow_diagram_section(): void
    {
        $this->seed([
            LanguagesSeeder::class,
            DomainsSeeder::class,
            ConceptsSeeder::class,
            FootwearConstructionConceptMediaSeeder::class,
        ]);

        $response = $this->get(route('glossary.concept', ['locale' => 'en', 'slug' => 'lasting']));
        $response->assertOk();
        $response->assertSee('data-concept-media', false);
        $response->assertSee('id="media-workflow-diagrams-heading"', false);
        $response->assertSee('toe, side, back-part (structured lines), and seat pull', false);
    }
}
