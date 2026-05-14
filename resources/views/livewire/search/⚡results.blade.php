<?php

use App\Models\Language;
use App\Support\GlossarySearch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    #[Locked]
    public string $locale = '';

    #[Url(as: 'q', history: true)]
    public string $q = '';

    public ?int $languageId = null;

    public function mount(string $locale): void
    {
        $this->locale = $locale;
        $this->languageId = Language::activeIdForCode($this->locale);
    }

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    protected function needle(): string
    {
        return mb_substr(trim($this->q), 0, 200);
    }

    public function getResultsProperty(): LengthAwarePaginator
    {
        return GlossarySearch::paginate($this->locale, $this->needle(), 15);
    }
}; ?>

@php
    $needle = $this->needle();
@endphp

<div class="space-y-8">
    <div class="space-y-4">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                {{ __('Search glossary') }}
            </p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                {{ __('Search results') }}
            </h1>
        </div>

        <div
            class="rounded-2xl border border-zinc-200 bg-white p-1.5 shadow-sm ring-1 ring-zinc-950/[0.03] transition-[border-color,box-shadow] duration-150 focus-within:border-zinc-300 focus-within:shadow-md dark:border-zinc-600/90 dark:bg-zinc-900 dark:ring-white/[0.04] dark:focus-within:border-zinc-500 sm:p-2 [&_input]:h-11 [&_input]:min-h-0 [&_input]:rounded-xl [&_input]:border-0 [&_input]:bg-transparent [&_input]:px-3 [&_input]:text-[14px] [&_input]:font-normal [&_input]:text-zinc-900 [&_input]:shadow-none [&_input]:ring-0 dark:[&_input]:text-zinc-100 [&_input]:focus:ring-0"
        >
            <flux:input
                wire:model.live.debounce.300ms="q"
                type="search"
                :label="__('Refine query')"
                :placeholder="__('Terms, definitions, domains…')"
                icon="magnifying-glass"
                autocomplete="off"
            />
        </div>
    </div>

    @if ($needle === '')
        <div
            class="rounded-xl border border-dashed border-zinc-200/90 bg-zinc-50/80 px-4 py-8 text-center dark:border-zinc-700 dark:bg-zinc-900/40"
            role="status"
        >
            <p class="text-[13px] font-medium text-zinc-800 dark:text-zinc-200">
                {{ __('Enter a term or phrase to search the glossary.') }}
            </p>
            <p class="mt-2 text-[12px] leading-relaxed text-zinc-500 dark:text-zinc-400">
                @if (\App\Support\GlossaryScoutQuery::usesMeilisearch())
                    {{ __('Results use the Meilisearch index (locale-filtered, typo-tolerant). Domains and related terms influence ranking.') }}
                @else
                    {{ __('Results are scoped to the current language. Related terms and domain labels are included in the match set.') }}
                @endif
            </p>
        </div>
    @elseif ($this->results->isEmpty())
        <div
            class="rounded-xl border border-zinc-200/90 bg-white px-4 py-8 text-center shadow-sm dark:border-zinc-700 dark:bg-zinc-900"
            role="status"
        >
            <p class="text-[13px] font-medium text-zinc-800 dark:text-zinc-200">
                {{ __('No results for ":q"', ['q' => $needle]) }}
            </p>
            <p class="mt-2 text-[12px] leading-relaxed text-zinc-500 dark:text-zinc-400">
                {{ __('Try a shorter fragment, another domain keyword, or browse the glossary by letter.') }}
            </p>
        </div>
    @else
        <section aria-labelledby="search-results-heading" class="space-y-4">
            <div class="flex flex-wrap items-end justify-between gap-3 border-b border-zinc-200/90 pb-4 dark:border-zinc-800">
                <div class="min-w-0">
                    <p id="search-results-heading" class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                        {{ __('Query') }}
                    </p>
                    <p class="mt-1 truncate text-[15px] font-semibold text-zinc-900 dark:text-zinc-50" title="{{ $needle }}">
                        {{ $needle }}
                    </p>
                </div>
                <p class="text-[12px] tabular-nums text-zinc-500 dark:text-zinc-400">
                    @if ($this->results->total() === 1)
                        {{ __('1 result') }}
                    @else
                        {{ __(':count results', ['count' => $this->results->total()]) }}
                    @endif
                </p>
            </div>

            <ul class="divide-y divide-zinc-200/90 dark:divide-zinc-800" role="list">
                @foreach ($this->results as $row)
                    @php
                        $snippet = $row->scoutHighlightedSnippet($needle);
                        $snippetVisible = trim(strip_tags((string) $snippet)) !== '';
                    @endphp
                    <li wire:key="search-hit-{{ $row->id }}" class="py-4 first:pt-0">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0 flex-1 space-y-1.5">
                                <h2 class="text-[15px] font-semibold leading-snug text-zinc-900 dark:text-zinc-50">
                                    <a
                                        href="{{ $row->glossaryUrl() }}"
                                        wire:navigate
                                        class="hover:underline decoration-zinc-400 underline-offset-2"
                                    >
                                        {!! $row->scoutHighlightedTerm($needle) !!}
                                    </a>
                                </h2>
                                @if ($snippetVisible)
                                    <p class="text-[13px] leading-relaxed text-zinc-600 dark:text-zinc-400">
                                        {!! $snippet !!}
                                    </p>
                                @endif
                            </div>
                        </div>
                        @if ($row->concept?->domains->isNotEmpty())
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                @foreach ($row->concept->domains as $domain)
                                    @php
                                        $label = $this->languageId !== null
                                            ? ($domain->translations->firstWhere('language_id', $this->languageId)?->name ?? $domain->slug)
                                            : $domain->slug;
                                    @endphp
                                    <flux:badge size="sm" variant="outline" wire:key="sd-{{ $row->id }}-{{ $domain->id }}">
                                        {{ $label }}
                                    </flux:badge>
                                @endforeach
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>

            @if ($this->results->hasPages())
                <div class="pt-2">
                    {{ $this->results->onEachSide(1)->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </section>
    @endif
</div>
