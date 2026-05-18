<?php

use App\Models\Concept;
use App\Models\ConceptTranslation;
use App\Models\Language;
use App\Support\Learning\RecordLearningProgress;
use App\Support\SemanticGraph;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public string $locale;

    public ?int $languageId = null;

    public ?int $conceptId = null;

    /** @var 'synonym'|'broader'|'narrower'|'related'|null */
    public ?string $bucket = null;

    public ?string $prompt = null;

    /** @var list<array{key: string, label: string}> */
    public array $choices = [];

    public ?string $correctKey = null;

    public ?string $selectedKey = null;

    public ?bool $graded = null;

    public ?int $anchorTranslationId = null;

    public function mount(string $locale): void
    {
        $this->locale = $locale;
        $this->languageId = Language::activeIdForCode($locale);
        $this->newRound();
    }

    public function newRound(): void
    {
        $this->graded = null;
        $this->selectedKey = null;
        $this->choices = [];
        $this->correctKey = null;
        $this->prompt = null;
        $this->bucket = null;
        $this->conceptId = null;
        $this->anchorTranslationId = null;

        if ($this->languageId === null) {
            return;
        }

        $order = ['synonym', 'broader', 'narrower', 'related'];

        foreach (collect($order)->shuffle() as $bucket) {
            $round = $this->buildRound((string) $bucket);
            if ($round !== null) {
                $this->bucket = $round['bucket'];
                $this->conceptId = $round['conceptId'];
                $this->prompt = $round['prompt'];
                $this->choices = $round['choices'];
                $this->correctKey = $round['correctKey'];
                $this->anchorTranslationId = $round['anchorTranslationId'];

                return;
            }
        }
    }

    /**
     * @return array{bucket: string, conceptId: int, prompt: string, choices: list<array{key: string, label: string}>, correctKey: string, anchorTranslationId: int}|null
     */
    private function buildRound(string $bucket): ?array
    {
        $languageId = $this->languageId;
        if ($languageId === null) {
            return null;
        }

        $mapKey = match ($bucket) {
            'synonym' => 'synonyms',
            'broader' => 'broader',
            'narrower' => 'narrower',
            'related' => 'related',
            default => null,
        };
        if ($mapKey === null) {
            return null;
        }

        $candidates = Concept::query()
            ->where('status', 'published')
            ->whereHas('translations', fn ($q) => $q->where('language_id', $languageId))
            ->inRandomOrder()
            ->limit(25)
            ->get();

        foreach ($candidates as $concept) {
            $concept->load([
                'translations' => fn ($q) => $q->where('language_id', $languageId),
                'outgoingRelations.relatedConcept.translations' => fn ($q) => $q->where('language_id', $languageId),
                'incomingRelations.concept.translations' => fn ($q) => $q->where('language_id', $languageId),
            ]);

            $anchor = $concept->translations->firstWhere('language_id', $languageId);
            if (! $anchor instanceof ConceptTranslation) {
                continue;
            }

            $grouped = SemanticGraph::peerTranslationsGrouped($concept, $languageId);
            /** @var \Illuminate\Support\Collection<int, ConceptTranslation> $peers */
            $peers = $grouped[$mapKey] ?? collect();
            if ($peers->isEmpty()) {
                continue;
            }

            $right = $peers->random();
            $pool = ConceptTranslation::query()
                ->forPublishedLocale($this->locale)
                ->where('id', '!=', $right->id)
                ->inRandomOrder()
                ->limit(12)
                ->get();

            $wrong = $pool
                ->reject(fn (ConceptTranslation $t): bool => $t->concept_id === $right->concept_id || $t->concept_id === $concept->id)
                ->take(2);

            $choices = collect([$right, ...$wrong->all()])->unique('id')->shuffle()->values();
            if ($choices->count() < 2) {
                continue;
            }

            $keys = ['a', 'b', 'c', 'd'];
            $rows = [];
            $correctKey = 'a';
            foreach ($choices as $i => $peer) {
                $key = $keys[$i] ?? (string) $i;
                $rows[] = ['key' => $key, 'label' => $peer->term];
                if ($peer->id === $right->id) {
                    $correctKey = $key;
                }
            }

            $prompt = match ($bucket) {
                'synonym' => __('learning.prompt_semantic_synonym', ['term' => $anchor->term]),
                'broader' => __('learning.prompt_semantic_broader', ['term' => $anchor->term]),
                'narrower' => __('learning.prompt_semantic_narrower', ['term' => $anchor->term]),
                default => __('learning.prompt_related_term', ['term' => $anchor->term]),
            };

            return [
                'bucket' => $bucket,
                'conceptId' => $concept->id,
                'prompt' => $prompt,
                'choices' => $rows,
                'correctKey' => $correctKey,
                'anchorTranslationId' => $anchor->id,
            ];
        }

        return null;
    }

    public function pick(string $key): void
    {
        if ($this->graded !== null) {
            return;
        }
        $this->selectedKey = $key;
    }

    public function check(): void
    {
        if ($this->correctKey === null || $this->selectedKey === null) {
            return;
        }
        $ok = $this->selectedKey === $this->correctKey;
        $this->graded = $ok;

        if ($ok && auth()->check() && $this->anchorTranslationId !== null) {
            $tr = ConceptTranslation::query()->with('concept')->find($this->anchorTranslationId);
            if ($tr !== null) {
                RecordLearningProgress::recordConceptReviewed(auth()->user(), $this->locale, $tr);
            }
        }
    }
}; ?>

<div class="space-y-6">
    @if ($languageId === null)
        @include('partials.ui.empty-state', ['message' => __('learning.locale_unavailable_semantic')])
    @elseif ($prompt === null)
        @include('partials.ui.empty-state', ['message' => __('learning.add_more_semantic_data')])
    @else
        @if ($bucket)
            <div class="flex items-center gap-2">
                @include('partials.ui.semantic-chip', ['label' => __('learning.semantic_drill'), 'interactive' => false])
                @include('partials.ui.status-badge', ['label' => strtoupper($bucket), 'status' => 'semantic'])
            </div>
        @endif
        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $prompt }}</p>

        <div class="space-y-2" role="radiogroup" aria-label="{{ __('learning.check_answer') }}">
            @foreach ($choices as $c)
                <button
                    type="button"
                    wire:click="pick('{{ $c['key'] }}')"
                    role="radio"
                    aria-checked="{{ $selectedKey === $c['key'] ? 'true' : 'false' }}"
                    class="w-full rounded-lg border px-4 py-3 text-left text-sm transition-colors
                        {{ $selectedKey === $c['key'] ? 'border-zinc-900 bg-zinc-50 dark:border-zinc-300 dark:bg-zinc-800' : 'border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-950' }} focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 focus-visible:ring-offset-2 dark:focus-visible:ring-zinc-500 dark:focus-visible:ring-offset-zinc-950"
                >
                    {{ $c['label'] }}
                </button>
            @endforeach
        </div>

        <div class="flex flex-wrap gap-3">
            <flux:button type="button" variant="primary" wire:click="check" :disabled="$selectedKey === null || $graded !== null">
                {{ __('learning.check_answer') }}
            </flux:button>
            <flux:button type="button" variant="ghost" wire:click="newRound">
                {{ __('learning.new_prompt') }}
            </flux:button>
        </div>

        @if ($graded !== null)
            <div
                role="status"
                aria-live="polite"
                class="rounded-lg border p-4 text-sm {{ $graded ? 'border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-100' : 'border-rose-200 bg-rose-50 text-rose-900 dark:border-rose-800 dark:bg-rose-950/40 dark:text-rose-100' }}"
            >
                {{ $graded ? __('learning.correct_relation') : __('learning.incorrect_relation') }}
            </div>
        @endif
    @endif
</div>
