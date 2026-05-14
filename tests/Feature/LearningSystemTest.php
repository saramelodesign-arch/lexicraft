<?php

namespace Tests\Feature;

use App\Models\Concept;
use App\Models\ConceptRelation;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LearningSystemTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function learning_hub_renders_and_includes_hreflang(): void
    {
        Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $r = $this->get(route('learning.index', ['locale' => 'en']));
        $r->assertOk();
        $r->assertSee('Learning lab', false);
        $r->assertSee('hreflang="en"', false);
    }

    #[Test]
    public function static_sitemap_includes_learning_urls(): void
    {
        Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $r = $this->get('/sitemaps/static-en.xml');
        $r->assertOk();
        $r->assertSee(route('learning.index', ['locale' => 'en'], absolute: true), false);
        $r->assertSee(route('learning.quizzes', ['locale' => 'en'], absolute: true), false);
    }

    #[Test]
    public function flashcards_page_renders_with_noindex(): void
    {
        Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $r = $this->get(route('learning.flashcards', ['locale' => 'en']));
        $r->assertOk();
        $r->assertSee('noindex', false);
    }

    #[Test]
    public function quiz_runner_grades_submission_and_records_attempt_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $en = Language::query()->create([
            'code' => 'en',
            'name' => 'English',
            'native_name' => 'English',
            'flag_icon' => null,
            'is_active' => true,
        ]);

        $quiz = Quiz::query()->create([
            'locale' => 'en',
            'slug' => 'test-quiz',
            'title' => 'Test',
            'description' => null,
            'domain_id' => null,
            'is_published' => true,
        ]);

        $question = QuizQuestion::query()->create([
            'quiz_id' => $quiz->id,
            'type' => 'multiple_choice',
            'payload' => [
                'prompt' => 'Pick one',
                'choices' => [
                    ['key' => 'a', 'label' => 'Wrong'],
                    ['key' => 'b', 'label' => 'Right'],
                ],
                'correct' => 'b',
            ],
            'sort_order' => 0,
        ]);

        Livewire::actingAs($user)
            ->test('learning.quiz-runner', ['quizId' => $quiz->id, 'locale' => 'en'])
            ->set('choiceAnswers.'.$question->id, 'b')
            ->call('submit')
            ->assertSet('submitted', true)
            ->assertSet('scorePercent', 100);

        $this->assertDatabaseHas('quiz_attempts', [
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score_percent' => 100,
        ]);
    }

    #[Test]
    public function semantic_practice_loads_when_synonyms_exist(): void
    {
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
            'term' => 'Alpha',
            'slug' => 'alpha',
            'short_definition' => 'One',
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
            'term' => 'Beta',
            'slug' => 'beta',
            'short_definition' => 'Two',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'seo_canonical_url' => null,
            'og_title' => null,
            'og_description' => null,
        ]);

        $c = Concept::query()->create([
            'status' => 'published',
            'difficulty_level' => null,
            'is_featured' => false,
        ]);

        ConceptTranslation::query()->create([
            'concept_id' => $c->id,
            'language_id' => $en->id,
            'term' => 'Gamma',
            'slug' => 'gamma',
            'short_definition' => 'Three',
            'full_definition' => null,
            'seo_title' => null,
            'seo_description' => null,
            'meta_keywords' => null,
            'industry_notes' => null,
            'seo_canonical_url' => null,
            'og_title' => null,
            'og_description' => null,
        ]);

        ConceptRelation::query()->create([
            'concept_id' => $a->id,
            'related_concept_id' => $b->id,
            'relation_type' => 'synonym',
        ]);

        $this->get(route('learning.semantic', ['locale' => 'en']))
            ->assertOk()
            ->assertSee('Semantic drill', false);
    }
}
