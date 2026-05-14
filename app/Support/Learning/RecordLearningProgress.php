<?php

namespace App\Support\Learning;

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\LearningProgress;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

final class RecordLearningProgress
{
    public static function record(?User $user, string $locale, string $action, ?Model $trackable = null, array $meta = []): void
    {
        if ($user === null) {
            return;
        }

        LearningProgress::query()->create([
            'user_id' => $user->id,
            'trackable_type' => $trackable instanceof Model ? $trackable::class : '',
            'trackable_id' => $trackable?->getKey(),
            'locale' => $locale,
            'action' => $action,
            'meta' => $meta !== [] ? $meta : null,
        ]);
    }

    public static function recordQuizComplete(?User $user, string $locale, Quiz $quiz, int $scorePercent, int $correct, int $total): void
    {
        self::record($user, $locale, 'quiz_completed', $quiz, [
            'score_percent' => $scorePercent,
            'correct' => $correct,
            'total' => $total,
        ]);
    }

    public static function recordFlashcardRound(?User $user, string $locale, int $cardsReviewed, ?string $domainSlug = null): void
    {
        self::record($user, $locale, 'flashcard_session', null, [
            'cards' => $cardsReviewed,
            'domain_slug' => $domainSlug,
        ]);
    }

    public static function recordConceptReviewed(?User $user, string $locale, ConceptTranslation $translation): void
    {
        $concept = $translation->concept;
        if ($concept instanceof Concept) {
            self::record($user, $locale, 'reviewed_concept', $concept, [
                'translation_id' => $translation->id,
                'slug' => $translation->slug,
            ]);
        }
    }

    /**
     * @return array{quizzes_completed: int, concepts_reviewed: int, flashcard_sessions: int}
     */
    public static function summaryForUser(User $user): array
    {
        $base = LearningProgress::query()->where('user_id', $user->id);

        return [
            'quizzes_completed' => (clone $base)->where('action', 'quiz_completed')->count(),
            'concepts_reviewed' => (clone $base)->where('action', 'reviewed_concept')->count(),
            'flashcard_sessions' => (clone $base)->where('action', 'flashcard_session')->count(),
        ];
    }
}
