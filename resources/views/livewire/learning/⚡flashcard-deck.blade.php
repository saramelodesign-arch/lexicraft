<?php

use App\Support\Learning\LearningFlashcardDeck;
use App\Support\Learning\RecordLearningProgress;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public string $locale;

    #[Locked]
    public ?int $domainId = null;

    #[Locked]
    public ?string $domainSlug = null;

    /** @var list<array<string, mixed>> */
    public array $cards = [];

    public int $index = 0;

    public bool $showBack = false;

    public function mount(string $locale, ?int $domainId = null, ?string $domainSlug = null): void
    {
        $this->locale = $locale;
        $this->domainId = $domainId;
        $this->domainSlug = $domainSlug;

        $this->cards = LearningFlashcardDeck::forLocale($locale, $domainId, limit: 40)
            ->values()
            ->all();
    }

    public function flip(): void
    {
        $this->showBack = ! $this->showBack;
    }

    public function next(): void
    {
        if ($this->index < count($this->cards) - 1) {
            $this->index++;
            $this->showBack = false;
        }
    }

    public function prev(): void
    {
        if ($this->index > 0) {
            $this->index--;
            $this->showBack = false;
        }
    }

    public function finishRound(): void
    {
        $n = count($this->cards);
        if ($n === 0) {
            return;
        }
        RecordLearningProgress::recordFlashcardRound(auth()->user(), $this->locale, $n, $this->domainSlug);
        $this->dispatch('flashcards-recorded');
    }
}; ?>

@php
    $card = $cards[$index] ?? null;
@endphp

<div class="space-y-6">
    @if ($card === null)
        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('learning.flashcards_empty') }}</p>
    @else
        <div class="text-center text-xs text-zinc-500 dark:text-zinc-500">
            {{ __('learning.card_of_total', ['i' => $index + 1, 'n' => count($cards)]) }}
        </div>

        <div
            class="relative min-h-[14rem] cursor-pointer rounded-xl border border-zinc-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 sm:min-h-[16rem] sm:p-8"
            role="button"
            tabindex="0"
            aria-pressed="{{ $showBack ? 'true' : 'false' }}"
            aria-label="{{ $showBack ? __('learning.definition_context') : __('learning.term') }}"
            wire:click="flip"
            wire:key="card-{{ $index }}-{{ $showBack ? 'b' : 'f' }}"
            @keydown.enter.prevent="$wire.flip()"
            @keydown.space.prevent="$wire.flip()"
        >
            @if (! $showBack)
                <p class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('learning.term') }}</p>
                <p class="mt-4 text-xl font-semibold tracking-tight text-zinc-900 sm:text-2xl dark:text-zinc-50">
                    {{ $card['term'] }}
                </p>
                @if (($card['domains'] ?? []) !== [])
                    <p class="mt-4 text-xs text-zinc-500 dark:text-zinc-400">
                        {{ __('learning.domain_list', ['d' => implode(', ', $card['domains'])]) }}
                    </p>
                @endif
            @else
                <p class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('learning.definition_context') }}</p>
                <p class="mt-4 text-sm leading-relaxed text-zinc-800 dark:text-zinc-200">
                    {{ $card['definition'] !== '' ? $card['definition'] : __('learning.no_short_definition') }}
                </p>
                @if (($card['examples'] ?? []) !== [])
                    <div class="mt-4 border-t border-zinc-200 pt-4 text-left dark:border-zinc-700">
                        <p class="text-[11px] font-semibold uppercase text-zinc-500">{{ __('learning.examples') }}</p>
                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-zinc-700 dark:text-zinc-300">
                            @foreach (array_slice($card['examples'], 0, 3) as $ex)
                                <li>{{ $ex }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (($card['semantic_hints'] ?? []) !== [])
                    <div class="mt-4 border-t border-zinc-200 pt-4 text-left dark:border-zinc-700">
                        <p class="text-[11px] font-semibold uppercase text-zinc-500">{{ __('learning.semantic_hints') }}</p>
                        <ul class="mt-2 space-y-1 text-xs text-zinc-600 dark:text-zinc-400">
                            @foreach (array_slice($card['semantic_hints'], 0, 6) as $hint)
                                <li>{{ $hint }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="mt-6 text-left">
                    @if (filled($card['glossary_url'] ?? null))
                        <flux:link href="{{ $card['glossary_url'] }}" wire:navigate class="text-sm font-medium focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 focus-visible:ring-offset-2 dark:focus-visible:ring-zinc-500 dark:focus-visible:ring-offset-zinc-950">
                            {{ __('learning.entry') }} →
                        </flux:link>
                    @else
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('learning.entry') }}</span>
                    @endif
                </div>
            @endif
            <p class="pointer-events-none mt-6 text-center text-[11px] text-zinc-400 dark:text-zinc-500">
                {{ __('learning.tap_to_flip') }}
            </p>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <flux:button type="button" variant="ghost" wire:click="prev" :disabled="$index === 0">
                {{ __('learning.previous') }}
            </flux:button>
            <flux:button type="button" variant="primary" wire:click="next" :disabled="$index >= count($cards) - 1">
                {{ __('learning.next') }}
            </flux:button>
        </div>

        @auth
            <flux:button type="button" variant="outline" size="sm" wire:click="finishRound">
                {{ __('learning.record_round') }}
            </flux:button>
        @else
            <p class="text-xs text-zinc-500 dark:text-zinc-500">{{ __('learning.signin_to_save_round') }}</p>
        @endauth
    @endif
</div>
