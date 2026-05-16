<?php

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Support\Learning\RecordLearningProgress;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public int $quizId;

    #[Locked]
    public string $locale;

    /** @var array<int, string|null> */
    public array $choiceAnswers = [];

    /** @var array<int, array<int, int|string|null>> */
    public array $matchAnswers = [];

    public bool $submitted = false;

    public int $correctCount = 0;

    public int $scorePercent = 0;

    public function mount(int $quizId, string $locale): void
    {
        $this->quizId = $quizId;
        $this->locale = $locale;

        $quiz = $this->resolvePublishedQuiz();

        foreach ($quiz->questions as $question) {
            if ($question->type === 'term_matching') {
                $this->matchAnswers[$question->id] = [];
                foreach ($question->payload['terms'] ?? [] as $term) {
                    $this->matchAnswers[$question->id][(int) $term['i']] = null;
                }
            }
        }
    }

    #[Computed]
    public function quiz(): Quiz
    {
        return $this->resolvePublishedQuiz();
    }

    private function resolvePublishedQuiz(): Quiz
    {
        return Quiz::resolvePublicByIdOrFail($this->locale, $this->quizId);
    }

    public function submit(): void
    {
        if ($this->submitted) {
            return;
        }

        $quiz = $this->quiz;
        $correct = 0;
        $total = $quiz->questions->count();

        foreach ($quiz->questions as $question) {
            if ($this->gradeQuestion($question)) {
                $correct++;
            }
        }

        $this->correctCount = $correct;
        $this->scorePercent = $total > 0 ? (int) round(($correct / $total) * 100) : 0;
        $this->submitted = true;

        $user = auth()->user();
        if ($user !== null) {
            QuizAttempt::query()->create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'locale' => $this->locale,
                'score_percent' => $this->scorePercent,
                'correct_count' => $correct,
                'total_count' => $total,
                'detail' => [
                    'choices' => $this->choiceAnswers,
                    'matching' => $this->matchAnswers,
                ],
                'completed_at' => now(),
            ]);
            RecordLearningProgress::recordQuizComplete($user, $this->locale, $quiz, $this->scorePercent, $correct, $total);
        }
    }

    private function gradeQuestion(QuizQuestion $question): bool
    {
        $payload = $question->payload;

        return match ($question->type) {
            'multiple_choice', 'definition_pick', 'semantic_pick' => ($this->choiceAnswers[$question->id] ?? null) === ($payload['correct'] ?? null),
            'term_matching' => $this->gradeMatching((array) $payload, $this->matchAnswers[$question->id] ?? []),
            default => false,
        };
    }

    /**
     * @param  array<int, int|string|null>  $selections keyed by term index
     */
    private function gradeMatching(array $payload, array $selections): bool
    {
        $terms = $payload['terms'] ?? [];
        if ($terms === []) {
            return false;
        }

        foreach ($terms as $term) {
            $i = (int) $term['i'];
            $got = $selections[$i] ?? null;
            if ((int) $got !== $i) {
                return false;
            }
        }

        return true;
    }
}; ?>

<div class="space-y-10">
    @if ($submitted)
        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-6 dark:border-zinc-700 dark:bg-zinc-900/50">
            <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('learning.results') }}</p>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('learning.correct_incorrect_summary', ['correct' => $correctCount, 'total' => $this->quiz->questions->count(), 'score' => $scorePercent]) }}
            </p>
            @guest
                <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-500">{{ __('learning.signin_to_record_progress') }}</p>
            @endguest
        </div>
    @endif

    @foreach ($this->quiz->questions as $q)
        <div class="rounded-xl border border-zinc-200 p-5 dark:border-zinc-800 sm:p-6" wire:key="q-{{ $q->id }}">
            <p class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                {{ __('learning.question_number', ['n' => $loop->iteration]) }}
                @if ($q->type === 'semantic_pick')
                    <span class="ms-2 normal-case text-zinc-400">— {{ __('learning.semantic') }}</span>
                @endif
            </p>
            @if (in_array($q->type, ['multiple_choice', 'definition_pick', 'semantic_pick'], true))
                @php $payload = $q->payload; @endphp
                <p class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $payload['prompt'] ?? '' }}</p>
                @if (! empty($payload['relation']))
                    <p class="mt-1 text-xs text-zinc-500">{{ __('learning.relation_focus', ['r' => $payload['relation']]) }}</p>
                @endif
                <div class="mt-4 space-y-2">
                    @foreach ($payload['choices'] ?? [] as $c)
                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-zinc-200 bg-white p-3 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                            <input
                                type="radio"
                                class="mt-1"
                                name="c-{{ $q->id }}"
                                value="{{ $c['key'] }}"
                                wire:model="choiceAnswers.{{ $q->id }}"
                                @disabled($submitted)
                            />
                            <span class="text-zinc-800 dark:text-zinc-200">{{ $c['label'] }}</span>
                        </label>
                    @endforeach
                </div>
            @elseif ($q->type === 'term_matching')
                @php $payload = $q->payload; @endphp
                <p class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $payload['prompt'] ?? '' }}</p>
                <div class="mt-4 space-y-4">
                    @foreach ($payload['terms'] ?? [] as $term)
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center" wire:key="mt-{{ $q->id }}-{{ $term['i'] }}">
                            <span class="min-w-[8rem] text-sm font-medium text-zinc-800 dark:text-zinc-200">{{ $term['term'] }}</span>
                            <select
                                class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900"
                                wire:model="matchAnswers.{{ $q->id }}.{{ $term['i'] }}"
                                @disabled($submitted)
                            >
                                <option value="">{{ __('learning.select_definition') }}</option>
                                @foreach ($payload['definitions'] ?? [] as $def)
                                    <option value="{{ $def['i'] }}">{{ $def['text'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach

    <div class="flex flex-wrap gap-3">
        @if (! $submitted)
            <flux:button type="button" variant="primary" wire:click="submit" wire:loading.attr="disabled">
                {{ __('learning.submit_answers') }}
            </flux:button>
        @endif
        <flux:button type="button" variant="ghost" :href="route('learning.quizzes', ['locale' => $locale])" wire:navigate>
            {{ __('learning.back_to_quizzes') }}
        </flux:button>
    </div>
</div>
