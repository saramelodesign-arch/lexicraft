<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Models\User;
use App\Support\Import\TerminologyImportPipeline;
use App\Support\Import\TerminologyImportRow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EditorialGovernanceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function concept_can_be_created_in_review_status(): void
    {
        $admin = User::factory()->admin()->create();
        $language = $this->createLanguage('en');

        $response = $this->actingAs($admin)->post(route('admin.concepts.store'), [
            'status' => 'review',
            'translation_status' => 'review',
            'language_id' => $language->id,
            'term' => 'Lasting',
            'slug' => 'lasting',
            'short_definition' => 'Operation used to pull the upper over the last.',
            'full_definition' => 'Detailed operation definition for lasting in industrial production.',
            'domain_ids' => [],
        ]);

        $response->assertSessionHasErrors(['domain_ids']);

        $domain = \App\Models\Domain::factory()->create(['is_active' => true, 'slug' => 'lasting-operations']);
        $response = $this->actingAs($admin)->post(route('admin.concepts.store'), [
            'status' => 'review',
            'translation_status' => 'review',
            'language_id' => $language->id,
            'term' => 'Lasting',
            'slug' => 'lasting',
            'short_definition' => 'Operation used to pull the upper over the last.',
            'full_definition' => 'Detailed operation definition for lasting in industrial production.',
            'domain_ids' => [$domain->id],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('concepts', ['status' => 'review']);
        $this->assertDatabaseHas('concept_translations', ['status' => 'review', 'slug' => 'lasting']);
    }

    #[Test]
    public function duplicate_term_is_blocked_for_same_locale_across_concepts(): void
    {
        $admin = User::factory()->admin()->create();
        $language = $this->createLanguage('en');
        $domain = \App\Models\Domain::factory()->create(['is_active' => true, 'slug' => 'assembly']);

        $first = Concept::query()->create(['status' => 'draft', 'difficulty_level' => null, 'is_featured' => false]);
        $first->domains()->sync([$domain->id]);
        ConceptTranslation::query()->create([
            'concept_id' => $first->id,
            'language_id' => $language->id,
            'status' => 'draft',
            'term' => 'Skiving',
            'slug' => 'skiving',
            'short_definition' => 'Reduce thickness of edge material.',
            'full_definition' => 'Industrial process reducing leather edge thickness for seam quality.',
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'seo_canonical_url' => null,
            'og_title' => null,
            'og_description' => null,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.concepts.store'), [
            'status' => 'draft',
            'translation_status' => 'draft',
            'language_id' => $language->id,
            'term' => 'Skiving',
            'slug' => 'skiving-duplicate',
            'short_definition' => 'Another definition.',
            'full_definition' => 'Another full definition for a conflicting concept.',
            'domain_ids' => [$domain->id],
        ]);

        $response->assertSessionHasErrors(['term']);
    }

    #[Test]
    public function placeholder_content_is_blocked_for_review_or_published_translations(): void
    {
        $admin = User::factory()->admin()->create();
        $language = $this->createLanguage('en');
        $domain = \App\Models\Domain::factory()->create(['is_active' => true, 'slug' => 'quality']);

        $response = $this->actingAs($admin)->post(route('admin.concepts.store'), [
            'status' => 'review',
            'translation_status' => 'review',
            'language_id' => $language->id,
            'term' => 'Inspection',
            'slug' => 'inspection',
            'short_definition' => 'lorem ipsum',
            'full_definition' => 'TBD',
            'domain_ids' => [$domain->id],
        ]);

        $response->assertSessionHasErrors(['short_definition', 'full_definition']);
    }

    #[Test]
    public function broader_cycle_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();
        $language = $this->createLanguage('en');

        $a = Concept::query()->create(['status' => 'published', 'difficulty_level' => null, 'is_featured' => false]);
        $b = Concept::query()->create(['status' => 'published', 'difficulty_level' => null, 'is_featured' => false]);

        ConceptTranslation::query()->create([
            'concept_id' => $a->id,
            'language_id' => $language->id,
            'status' => 'published',
            'term' => 'Upper assembly',
            'slug' => 'upper-assembly',
            'short_definition' => 'Upper assembly phase.',
            'full_definition' => 'Detailed definition for upper assembly.',
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'seo_canonical_url' => null,
            'og_title' => null,
            'og_description' => null,
        ]);
        ConceptTranslation::query()->create([
            'concept_id' => $b->id,
            'language_id' => $language->id,
            'status' => 'published',
            'term' => 'Finishing',
            'slug' => 'finishing',
            'short_definition' => 'Finishing stage.',
            'full_definition' => 'Detailed definition for finishing.',
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'seo_canonical_url' => null,
            'og_title' => null,
            'og_description' => null,
        ]);

        \App\Models\ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'broader',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.concepts.relations.store', $b), [
            'relation_type' => 'broader',
            'related_locale' => 'en',
            'related_slug' => 'upper-assembly',
        ]);

        $response->assertSessionHasErrors(['relation_type']);
    }

    #[Test]
    public function import_pipeline_reports_validation_errors_for_invalid_rows(): void
    {
        $this->createLanguage('en');

        $pipeline = app(TerminologyImportPipeline::class);
        $summary = $pipeline->import([
            new TerminologyImportRow(
                locale: 'en',
                term: 'Term A',
                slug: 'term-a',
                shortDefinition: 'lorem ipsum',
                fullDefinition: 'placeholder text',
                conceptStatus: 'published',
                translationStatus: 'published',
                domains: [],
            ),
        ], dryRun: true);

        $this->assertSame(1, $summary['processed']);
        $this->assertSame(1, $summary['skipped']);
        $this->assertNotEmpty($summary['errors']);
    }

    private function createLanguage(string $code): Language
    {
        return Language::query()->create([
            'code' => $code,
            'name' => strtoupper($code),
            'native_name' => strtoupper($code),
            'flag_icon' => null,
            'is_active' => true,
        ]);
    }
}
