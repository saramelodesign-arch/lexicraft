<?php

namespace App\Support\Learning;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Support\SemanticGraph;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Generates sample industrial quizzes from published glossary rows (seed / maintenance).
 */
final class LearningQuizGenerator
{
    public const string SAMPLE_SLUG = 'industrial-terminology-check';

    public static function regenerateForLocale(string $locale): ?Quiz
    {
        $languageId = Language::activeIdForCode($locale);
        if ($languageId === null) {
            return null;
        }

        $pool = ConceptTranslation::query()
            ->forPublishedLocale($locale)
            ->with([
                'concept.outgoingRelations.relatedConcept.translations' => fn ($q) => $q->where('language_id', $languageId),
                'concept.incomingRelations.concept.translations' => fn ($q) => $q->where('language_id', $languageId),
            ])
            ->inRandomOrder()
            ->limit(40)
            ->get();

        if ($pool->count() < 6) {
            return null;
        }

        QuizQuestion::query()->whereHas('quiz', fn ($q) => $q->where('locale', $locale)->where('slug', self::SAMPLE_SLUG))->delete();
        Quiz::query()->where('locale', $locale)->where('slug', self::SAMPLE_SLUG)->delete();

        app()->setLocale($locale);

        $quiz = Quiz::query()->create([
            'locale' => $locale,
            'slug' => self::SAMPLE_SLUG,
            'title' => __('Industrial terminology check'),
            'description' => __('Multiple choice, definitions, semantic relations, and matching—grounded in published glossary entries.'),
            'domain_id' => null,
            'is_published' => true,
        ]);

        $sort = 0;

        foreach ($pool->take(4) as $translation) {
            $payload = self::definitionPickPayload($translation, $pool, $languageId);
            if ($payload !== null) {
                QuizQuestion::query()->create([
                    'quiz_id' => $quiz->id,
                    'type' => 'definition_pick',
                    'payload' => $payload,
                    'sort_order' => $sort++,
                ]);
            }
        }

        $semantic = self::semanticPickFromPool($pool, $languageId);
        if ($semantic !== null) {
            QuizQuestion::query()->create([
                'quiz_id' => $quiz->id,
                'type' => 'semantic_pick',
                'payload' => $semantic,
                'sort_order' => $sort++,
            ]);
        }

        $mc = self::multipleChoiceFromPool($pool);
        if ($mc !== null) {
            QuizQuestion::query()->create([
                'quiz_id' => $quiz->id,
                'type' => 'multiple_choice',
                'payload' => $mc,
                'sort_order' => $sort++,
            ]);
        }

        $match = self::matchingPayload($pool->take(8));
        if ($match !== null) {
            QuizQuestion::query()->create([
                'quiz_id' => $quiz->id,
                'type' => 'term_matching',
                'payload' => $match,
                'sort_order' => $sort++,
            ]);
        }

        return $quiz->load('questions');
    }

    /**
     * @param  Collection<int, ConceptTranslation>  $pool
     * @return array<string, mixed>|null
     */
    private static function definitionPickPayload(ConceptTranslation $target, Collection $pool, int $languageId): ?array
    {
        $correct = Str::limit(strip_tags((string) ($target->short_definition ?? $target->full_definition ?? '')), 220, '…');
        if ($correct === '') {
            return null;
        }

        $distractors = $pool
            ->reject(fn (ConceptTranslation $t): bool => $t->id === $target->id)
            ->map(fn (ConceptTranslation $t): string => Str::limit(strip_tags((string) ($t->short_definition ?? $t->full_definition ?? $t->term)), 220, '…'))
            ->filter(fn (string $d): bool => $d !== '' && $d !== $correct)
            ->unique()
            ->shuffle()
            ->take(3)
            ->values()
            ->all();

        if (count($distractors) < 2) {
            return null;
        }

        $choices = collect([$correct, ...array_slice($distractors, 0, 3)])
            ->unique()
            ->shuffle()
            ->values();

        $keys = ['a', 'b', 'c', 'd'];
        $choiceRows = [];
        $correctKey = 'a';
        foreach ($choices as $i => $label) {
            $key = $keys[$i] ?? (string) $i;
            $choiceRows[] = ['key' => $key, 'label' => $label];
            if ($label === $correct) {
                $correctKey = $key;
            }
        }

        return [
            'prompt' => __('Which definition best matches the term “:term”?', ['term' => $target->term]),
            'choices' => $choiceRows,
            'correct' => $correctKey,
        ];
    }

    /**
     * @param  Collection<int, ConceptTranslation>  $pool
     * @return array<string, mixed>|null
     */
    private static function semanticPickFromPool(Collection $pool, int $languageId): ?array
    {
        foreach ($pool as $translation) {
            $concept = $translation->concept;
            if (! $concept instanceof Concept) {
                continue;
            }
            $grouped = SemanticGraph::peerTranslationsGrouped($concept, $languageId);
            $synonyms = $grouped['synonyms'] ?? collect();
            if ($synonyms->isEmpty()) {
                continue;
            }
            $right = $synonyms->first();
            $wrong = $pool
                ->reject(fn (ConceptTranslation $t): bool => $t->concept_id === $right->concept_id || $t->concept_id === $concept->id)
                ->shuffle()
                ->take(3);

            $choices = collect([$right, ...$wrong])
                ->filter()
                ->unique('id')
                ->shuffle()
                ->values();

            $keys = ['a', 'b', 'c', 'd'];
            $choiceRows = [];
            $correctKey = 'a';
            foreach ($choices as $i => $peer) {
                if (! $peer instanceof ConceptTranslation) {
                    continue;
                }
                $key = $keys[$i] ?? (string) $i;
                $choiceRows[] = ['key' => $key, 'label' => $peer->term];
                if ($peer->id === $right->id) {
                    $correctKey = $key;
                }
            }

            if (count($choiceRows) < 3) {
                continue;
            }

            return [
                'prompt' => __('Which term is a semantic synonym of “:term”?', ['term' => $translation->term]),
                'relation' => 'synonym',
                'choices' => $choiceRows,
                'correct' => $correctKey,
            ];
        }

        return null;
    }

    /**
     * @param  Collection<int, ConceptTranslation>  $pool
     * @return array<string, mixed>|null
     */
    private static function multipleChoiceFromPool(Collection $pool): ?array
    {
        $target = $pool->shuffle()->first(fn (ConceptTranslation $t): bool => trim((string) ($t->short_definition ?? '')) !== '' || trim((string) ($t->full_definition ?? '')) !== '');
        if ($target === null) {
            return null;
        }

        $snippet = Str::limit(strip_tags((string) ($target->short_definition ?? $target->full_definition ?? '')), 160, '…');
        if ($snippet === '') {
            return null;
        }

        $wrong = $pool
            ->reject(fn (ConceptTranslation $t): bool => $t->id === $target->id)
            ->shuffle()
            ->take(3);

        $choices = collect([$target, ...$wrong->all()])
            ->unique('id')
            ->shuffle()
            ->values();

        $keys = ['a', 'b', 'c', 'd'];
        $choiceRows = [];
        $correctKey = 'a';
        foreach ($choices as $i => $peer) {
            $key = $keys[$i] ?? (string) $i;
            $choiceRows[] = ['key' => $key, 'label' => $peer->term];
            if ($peer->id === $target->id) {
                $correctKey = $key;
            }
        }

        return [
            'prompt' => __('Which term is described by: “:snippet”?', ['snippet' => $snippet]),
            'choices' => $choiceRows,
            'correct' => $correctKey,
        ];
    }

    /**
     * @param  Collection<int, ConceptTranslation>  $pool
     * @return array<string, mixed>|null
     */
    private static function matchingPayload(Collection $pool): ?array
    {
        $pick = $pool->filter(fn (ConceptTranslation $t): bool => filled($t->short_definition ?? $t->full_definition))->take(3);
        if ($pick->count() < 3) {
            return null;
        }

        $terms = [];
        $defs = [];
        $map = [];

        foreach ($pick->values() as $idx => $translation) {
            $def = Str::limit(strip_tags((string) ($translation->short_definition ?? $translation->full_definition ?? '')), 200, '…');
            $terms[] = ['i' => $idx, 'term' => $translation->term];
            $defs[] = ['i' => $idx, 'text' => $def];
        }

        return [
            'prompt' => __('Match each term to its definition.'),
            'terms' => $terms,
            'definitions' => array_values($defs),
        ];
    }
}
