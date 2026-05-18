<?php

namespace Tests\Feature;

use Database\Seeders\ConceptsSeeder;
use Database\Seeders\DomainsSeeder;
use Database\Seeders\LanguagesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FootwearWorkflowNavigationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function cementing_page_shows_upstream_and_downstream_workflow_neighbors(): void
    {
        $this->seed([
            LanguagesSeeder::class,
            DomainsSeeder::class,
            ConceptsSeeder::class,
        ]);

        $this->get(route('glossary.concept', ['locale' => 'en', 'slug' => 'cementing']))
            ->assertOk()
            ->assertSee('Process workflow', false)
            ->assertSee('Upstream (before)', false)
            ->assertSee('Downstream (after)', false)
            ->assertSee('Heat activation', false)
            ->assertSee('id="semantic-workflow"', false);
    }

    #[Test]
    public function goodyear_welt_page_shows_route_alternative_navigation(): void
    {
        $this->seed([
            LanguagesSeeder::class,
            DomainsSeeder::class,
            ConceptsSeeder::class,
        ]);

        $this->get(route('glossary.concept', ['locale' => 'en', 'slug' => 'goodyear-welt']))
            ->assertOk()
            ->assertSee('Route alternatives', false)
            ->assertSee('Blake stitch', false);
    }
}
