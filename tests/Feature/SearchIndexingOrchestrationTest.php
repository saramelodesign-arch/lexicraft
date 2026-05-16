<?php

namespace Tests\Feature;

use App\Jobs\RemoveConceptTranslationsFromSearchIndexJob;
use App\Jobs\SyncConceptSearchIndexJob;
use App\Jobs\SyncConceptTranslationSearchIndexJob;
use App\Models\Concept;
use App\Models\ConceptRelation;
use App\Models\ConceptTranslation;
use App\Models\Domain;
use App\Models\Language;
use App\Support\Import\TerminologyImportPipeline;
use App\Support\Import\TerminologyImportRow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SearchIndexingOrchestrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function concept_translation_save_and_delete_dispatch_translation_index_jobs(): void
    {
        Queue::fake();

        $language = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
        ]);

        $concept = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        $translation = ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $language->id,
            'status' => 'published',
            'term' => 'Lasting',
            'slug' => 'lasting',
        ]);

        Queue::assertPushed(SyncConceptTranslationSearchIndexJob::class);

        $translation->delete();

        Queue::assertPushed(SyncConceptTranslationSearchIndexJob::class, function (SyncConceptTranslationSearchIndexJob $job) use ($translation): bool {
            return $job->translationId === (int) $translation->id;
        });
    }

    #[Test]
    public function concept_status_update_dispatches_concept_reindex_job(): void
    {
        Queue::fake();

        $concept = Concept::query()->create([
            'status' => 'draft',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        $concept->update(['status' => 'published']);

        Queue::assertPushed(SyncConceptSearchIndexJob::class);
    }

    #[Test]
    public function concept_delete_dispatches_index_removal_job_for_existing_translations(): void
    {
        Queue::fake();

        $language = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
        ]);

        $concept = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $concept->id,
            'language_id' => $language->id,
            'status' => 'published',
            'term' => 'Skiving',
            'slug' => 'skiving',
        ]);

        $concept->delete();

        Queue::assertPushed(RemoveConceptTranslationsFromSearchIndexJob::class);
    }

    #[Test]
    public function semantic_relation_updates_dispatch_concept_reindex_for_both_nodes(): void
    {
        Queue::fake();

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

        $relation = ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'related',
        ]);

        Queue::assertPushed(SyncConceptSearchIndexJob::class, 2);

        $relation->delete();

        Queue::assertPushed(SyncConceptSearchIndexJob::class, function (SyncConceptSearchIndexJob $job) use ($a, $b): bool {
            return in_array($job->conceptId, [(int) $a->id, (int) $b->id], true);
        });
    }

    #[Test]
    public function import_pipeline_updates_dispatch_index_jobs_via_model_events(): void
    {
        Queue::fake();

        Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'is_active' => true,
        ]);

        Domain::query()->create([
            'slug' => 'footwear',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $pipeline = new TerminologyImportPipeline;
        $pipeline->import([
            new TerminologyImportRow(
                locale: 'en',
                term: 'Bottoming',
                slug: 'bottoming',
                shortDefinition: 'Bottoming definition.',
                fullDefinition: 'Bottoming full definition.',
                conceptStatus: 'published',
                translationStatus: 'published',
                domains: ['footwear'],
            ),
        ], dryRun: false);

        Queue::assertPushed(SyncConceptTranslationSearchIndexJob::class);
    }
}

