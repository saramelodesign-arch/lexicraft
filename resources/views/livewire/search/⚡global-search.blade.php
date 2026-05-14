<?php

use App\Support\GlossarySearch;
use App\Support\Locales;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public string $query = '';

    public int $highlightIndex = 0;

    public bool $panelOpen = false;

    #[On('quick-search-term')]
    public function applyQuickTerm(string $term): void
    {
        $this->query = $term;
        $this->panelOpen = strlen(trim($term)) > 0;
        $this->highlightIndex = 0;
    }

    public function updatedQuery(string $value): void
    {
        $this->panelOpen = strlen(trim($value)) > 0;
        $this->highlightIndex = 0;
    }

    #[Computed]
    public function dropdownItems()
    {
        return GlossarySearch::dropdown(Locales::current(), trim($this->query), 8);
    }

    public function selectNext(): void
    {
        if (! $this->panelOpen) {
            return;
        }

        $count = $this->dropdownItems->count();
        if ($count === 0) {
            return;
        }

        $this->highlightIndex = ($this->highlightIndex + 1) % $count;
    }

    public function selectPrevious(): void
    {
        if (! $this->panelOpen) {
            return;
        }

        $count = $this->dropdownItems->count();
        if ($count === 0) {
            return;
        }

        $this->highlightIndex = ($this->highlightIndex - 1 + $count) % $count;
    }

    public function openPanel(): void
    {
        if (strlen(trim($this->query)) > 0) {
            $this->panelOpen = true;
        }
    }

    public function closePanel(): void
    {
        $this->panelOpen = false;
    }

    public function setHighlight(int $index): void
    {
        $this->highlightIndex = $index;
    }

    public function submitSearch()
    {
        $q = trim($this->query);
        if ($q === '') {
            $this->closePanel();

            return null;
        }

        $list = GlossarySearch::dropdown(Locales::current(), $q, 8);
        if ($list->isNotEmpty()) {
            $row = $list->get($this->highlightIndex) ?? $list->first();
            if ($row !== null) {
                $this->closePanel();

                return $this->redirect($row->glossaryUrl());
            }
        }

        $this->closePanel();

        return $this->redirect(route('search', ['locale' => Locales::current(), 'q' => $q]));
    }

    public function viewAllResults()
    {
        $q = trim($this->query);
        if ($q === '') {
            return null;
        }
        $this->closePanel();

        return $this->redirect(route('search', ['locale' => Locales::current(), 'q' => $q]));
    }
}; ?>

@php
    $trimmedQuery = trim($query);
@endphp

<div
    id="site-search"
    class="relative w-full"
    x-on:focusin="$wire.openPanel()"
    @click.outside="$wire.closePanel()"
    @keydown.escape.window="$wire.closePanel()"
    wire:keydown.arrow-down.prevent="selectNext"
    wire:keydown.arrow-up.prevent="selectPrevious"
    wire:keydown.enter.prevent="submitSearch"
>
    <div
        class="w-full max-w-full rounded-2xl border border-zinc-200 bg-white p-1.5 shadow-sm ring-1 ring-zinc-950/[0.03] transition-[border-color,box-shadow,ring-color] duration-150 focus-within:border-zinc-300 focus-within:shadow-md focus-within:ring-zinc-950/[0.05] dark:border-zinc-600/90 dark:bg-zinc-900 dark:ring-white/[0.04] dark:focus-within:border-zinc-500 dark:focus-within:shadow-lg dark:focus-within:ring-white/[0.06] sm:p-2 [&_input]:h-12 [&_input]:min-h-0 [&_input]:rounded-xl [&_input]:border-0 [&_input]:bg-transparent [&_input]:px-3 [&_input]:text-[14px] [&_input]:font-normal [&_input]:leading-none [&_input]:text-zinc-900 [&_input]:shadow-none [&_input]:ring-0 [&_input]:placeholder:text-[13px] [&_input]:placeholder:font-normal [&_input]:placeholder:text-zinc-400 [&_input]:placeholder:tracking-tight [&_input]:focus:ring-0 dark:[&_input]:text-zinc-100 dark:[&_input]:placeholder:text-zinc-500 [&_label]:sr-only"
    >
        <flux:input
            wire:model.live.debounce.300ms="query"
            type="search"
            :label="__('Search glossary')"
            :placeholder="__('Search terms, definitions, translations…')"
            icon="magnifying-glass"
            autocomplete="off"
        />
    </div>

    @if ($panelOpen && $this->dropdownItems->isNotEmpty())
        <div
            class="absolute z-40 mt-2 max-h-[min(18rem,calc(100vh-9rem))] w-full overflow-hidden rounded-xl border border-zinc-200/90 bg-white shadow-md shadow-zinc-900/10 ring-1 ring-zinc-950/[0.03] dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-lg dark:shadow-black/40 dark:ring-white/[0.04]"
            role="listbox"
            aria-label="{{ __('Search suggestions') }}"
        >
            <ul class="max-h-[min(17rem,calc(100vh-10rem))] divide-y divide-zinc-100 overflow-y-auto overscroll-contain dark:divide-zinc-800/80">
                @foreach ($this->dropdownItems as $index => $row)
                    @php
                        $snippet = $row->scoutHighlightedSnippet($trimmedQuery);
                        $snippetVisible = trim(strip_tags((string) $snippet)) !== '';
                    @endphp
                    <li wire:key="dd-{{ $row->id }}">
                        <a
                            href="{{ $row->glossaryUrl() }}"
                            wire:navigate
                            role="option"
                            aria-selected="{{ $index === $highlightIndex ? 'true' : 'false' }}"
                            @class([
                                'flex flex-col gap-1 px-3.5 py-2.5 text-left text-[13px] transition-colors outline-none',
                                'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-zinc-50' => $index === $highlightIndex,
                                'text-zinc-800 hover:bg-zinc-50 dark:text-zinc-200 dark:hover:bg-zinc-800/60' => $index !== $highlightIndex,
                            ])
                            wire:mouseenter="setHighlight({{ $index }})"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <span class="min-w-0 font-semibold leading-snug">
                                    {!! $row->scoutHighlightedTerm($trimmedQuery) !!}
                                </span>
                                <flux:icon.chevron-right variant="micro" class="mt-0.5 shrink-0 text-zinc-400 dark:text-zinc-500" />
                            </div>
                            @if ($snippetVisible)
                                <p class="line-clamp-2 text-[12px] leading-relaxed text-zinc-600 dark:text-zinc-400">
                                    {!! $snippet !!}
                                </p>
                            @endif
                            @if ($row->concept?->domains->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($row->concept->domains as $domain)
                                        @php
                                            $langId = $row->language_id;
                                            $label = $domain->translations->firstWhere('language_id', $langId)?->name ?? $domain->slug;
                                        @endphp
                                        <span
                                            class="rounded border border-zinc-200/80 bg-zinc-50 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-zinc-500 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-400"
                                            wire:key="dd-d-{{ $row->id }}-{{ $domain->id }}"
                                        >
                                            {{ $label }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="border-t border-zinc-100 dark:border-zinc-800">
                <button
                    type="button"
                    wire:click="viewAllResults"
                    class="flex w-full items-center justify-between gap-2 px-3.5 py-2.5 text-left text-[12px] font-medium text-zinc-700 transition-colors hover:bg-zinc-50 dark:text-zinc-300 dark:hover:bg-zinc-800/60"
                >
                    <span>{{ __('View all results') }}</span>
                    <flux:icon.arrow-right variant="micro" class="shrink-0 text-zinc-400" />
                </button>
                <p class="border-t border-zinc-100 px-3.5 py-2 text-[11px] leading-snug text-zinc-500 dark:border-zinc-800 dark:text-zinc-500">
                    @if (\App\Support\GlossaryScoutQuery::usesMeilisearch())
                        {{ __('Meilisearch index: typo-tolerant, locale-filtered LexiCraft Glossary discovery.') }}
                    @else
                        {{ __('Lexical SQL search in this locale. Set SCOUT_DRIVER=meilisearch for typo-tolerant ranking.') }}
                    @endif
                </p>
            </div>
        </div>
    @elseif ($panelOpen && strlen($trimmedQuery) > 0)
        <div
            class="absolute z-40 mt-2 w-full rounded-xl border border-zinc-200/90 bg-white p-3.5 text-[13px] leading-relaxed text-zinc-600 shadow-md shadow-zinc-900/10 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400"
            role="status"
        >
            <p>{{ __('No glossary matches for that phrase in this language.') }}</p>
            <button
                type="button"
                wire:click="submitSearch"
                class="mt-3 text-[12px] font-semibold text-zinc-900 underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-500 dark:text-zinc-200"
            >
                {{ __('Open search results page') }}
            </button>
        </div>
    @endif
</div>
