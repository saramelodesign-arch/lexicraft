<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminConceptManagementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_create_concept_with_first_translation(): void
    {
        $user = User::factory()->admin()->create();

        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('admin.concepts.store'), [
            'status' => 'draft',
            'language_id' => $en->id,
            'term' => 'Lasting',
            'slug' => 'lasting',
            'short_definition' => 'Test',
            'is_featured' => '0',
        ]);

        $response->assertRedirect();

        $concept = Concept::query()->first();
        $this->assertNotNull($concept);
        $this->assertSame('draft', $concept->status);
        $this->assertSame('Lasting', $concept->translations->first()?->term);
    }

    #[Test]
    public function admin_can_add_semantic_relation(): void
    {
        $user = User::factory()->admin()->create();

        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
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

        ConceptTranslation::query()->create([
            'concept_id' => $a->id,
            'language_id' => $en->id,
            'term' => 'A',
            'slug' => 'term-a',
            'short_definition' => null,
            'full_definition' => null,
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
            'language_id' => $en->id,
            'term' => 'B',
            'slug' => 'term-b',
            'short_definition' => null,
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'seo_canonical_url' => null,
            'og_title' => null,
            'og_description' => null,
        ]);

        $this->actingAs($user)
            ->post(route('admin.concepts.relations.store', $a), [
                'relation_type' => 'related',
                'related_locale' => 'en',
                'related_slug' => 'term-b',
            ])
            ->assertRedirect();

        $this->assertSame(1, $a->outgoingRelations()->count());
    }
}
