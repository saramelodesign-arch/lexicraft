<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public string $query = '';

    public int $highlightIndex = 0;

    public bool $panelOpen = false;

    /** @var array<int, array{label: string, type: string}> */
    protected array $placeholderCatalog = [
        ['label' => 'Last', 'type' => 'Term'],
        ['label' => 'Feather edge', 'type' => 'Term'],
        ['label' => 'Skiving', 'type' => 'Term'],
        ['label' => 'Pattern making', 'type' => 'Domain'],
        ['label' => 'CAD nesting', 'type' => 'Term'],
        ['label' => 'Leather splitting', 'type' => 'Term'],
    ];

    public function updatedQuery(string $value): void
    {
        $this->panelOpen = strlen(trim($value)) > 0;
        $this->highlightIndex = 0;
    }

    public function selectNext(): void
    {
        if (! $this->panelOpen) {
            return;
        }

        $count = count($this->filteredResults);
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

        $count = count($this->filteredResults);
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

    #[Computed]
    public function filteredResults(): array
    {
        $q = mb_strtolower(trim($this->query));

        if ($q === '') {
            return [];
        }

        return array_values(array_filter(
            $this->placeholderCatalog,
            fn (array $row): bool => str_contains(mb_strtolower($row['label']), $q)
                || str_contains(mb_strtolower($row['type']), $q),
        ));
    }
}; ?>

<div
    class="relative w-full"
    x-on:focusin="$wire.openPanel()"
    @click.outside="$wire.closePanel()"
    @keydown.escape.window="$wire.closePanel()"
    wire:keydown.arrow-down.prevent="selectNext"
    wire:keydown.arrow-up.prevent="selectPrevious"
    wire:keydown.enter.prevent="closePanel"
>
    <div
        class="rounded-2xl border border-zinc-200/90 bg-white p-3 shadow-[0_1px_2px_rgba(9,9,11,0.05)] ring-1 ring-zinc-950/[0.04] transition-[border-color,box-shadow] focus-within:border-zinc-300 focus-within:shadow-[0_1px_3px_rgba(9,9,11,0.08)] focus-within:ring-zinc-950/[0.07] dark:border-zinc-700/90 dark:bg-zinc-900/60 dark:shadow-[0_1px_2px_rgba(0,0,0,0.35)] dark:ring-white/[0.06] dark:focus-within:border-zinc-500 dark:focus-within:ring-white/[0.08]"
    >
        <flux:input
            wire:model.live.debounce.300ms="query"
            type="search"
            :label="__('Search')"
            :placeholder="__('Terms, definitions, translations…')"
            icon="magnifying-glass"
            autocomplete="off"
        />
    </div>

    @if ($panelOpen && count($this->filteredResults) > 0)
        <div
            class="absolute z-40 mt-2 w-full overflow-hidden rounded-xl border border-zinc-200/90 bg-white shadow-md shadow-zinc-900/10 ring-1 ring-zinc-950/[0.03] dark:border-zinc-700 dark:bg-zinc-900 dark:shadow-lg dark:shadow-black/40 dark:ring-white/[0.04]"
            role="listbox"
            aria-label="{{ __('Search suggestions') }}"
        >
            <ul class="max-h-60 divide-y divide-zinc-100 overflow-y-auto dark:divide-zinc-800/80">
                @foreach ($this->filteredResults as $index => $row)
                    <li
                        wire:key="result-{{ $row['label'] }}"
                        role="option"
                        aria-selected="{{ $index === $highlightIndex ? 'true' : 'false' }}"
                        @class([
                            'flex cursor-pointer items-center justify-between gap-3 px-3.5 py-2 text-[13px] transition-colors',
                            'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-zinc-50' => $index === $highlightIndex,
                            'text-zinc-800 hover:bg-zinc-50 dark:text-zinc-200 dark:hover:bg-zinc-800/60' => $index !== $highlightIndex,
                        ])
                        wire:mouseenter="setHighlight({{ $index }})"
                    >
                        <span class="font-medium">{{ $row['label'] }}</span>
                        <span class="shrink-0 rounded border border-zinc-200/80 bg-zinc-50 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-zinc-500 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-400">
                            {{ __($row['type']) }}
                        </span>
                    </li>
                @endforeach
            </ul>
            <p class="border-t border-zinc-100 px-3.5 py-2 text-[11px] leading-snug text-zinc-500 dark:border-zinc-800 dark:text-zinc-500">
                {{ __('Preview results. Full-text search will use your index when connected.') }}
            </p>
        </div>
    @elseif ($panelOpen && strlen(trim($query)) > 0)
        <div class="absolute z-40 mt-2 w-full rounded-xl border border-zinc-200/90 bg-white p-3.5 text-[13px] leading-relaxed text-zinc-600 shadow-md shadow-zinc-900/10 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400">
            {{ __('No matches in the preview set. Try another keyword.') }}
        </div>
    @endif
</div>
