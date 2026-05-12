<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    /**
     * @return array<int, array{key: string, abbr: string, title: string, description: string, count: string}>
     */
    #[Computed]
    public function domains(): array
    {
        return [
            ['key' => 'footwear', 'abbr' => 'FW', 'title' => __('Footwear'), 'description' => __('Lasting, soling, closing, and upper construction.'), 'count' => '—'],
            ['key' => 'leather-goods', 'abbr' => 'LG', 'title' => __('Leather goods'), 'description' => __('Bags, SLG, and assembly vocabulary.'), 'count' => '—'],
            ['key' => 'belts', 'abbr' => 'BL', 'title' => __('Belts'), 'description' => __('Straps, buckles, edges, and hardware.'), 'count' => '—'],
            ['key' => 'design', 'abbr' => 'DS', 'title' => __('Design'), 'description' => __('Specs, colorways, and industrial design.'), 'count' => '—'],
            ['key' => 'pattern-making', 'abbr' => 'PM', 'title' => __('Pattern making'), 'description' => __('Grading, markers, allowances, templates.'), 'count' => '—'],
            ['key' => 'cad-cam', 'abbr' => 'CAD', 'title' => __('CAD/CAM'), 'description' => __('Nesting, digitizing, paths, machine prep.'), 'count' => '—'],
            ['key' => 'materials', 'abbr' => 'MT', 'title' => __('Materials'), 'description' => __('Uppers, linings, reinforcements, compounds.'), 'count' => '—'],
            ['key' => 'leather', 'abbr' => 'LR', 'title' => __('Leather'), 'description' => __('Tannage, selection, defects, finishing.'), 'count' => '—'],
            ['key' => 'machinery', 'abbr' => 'MC', 'title' => __('Machinery'), 'description' => __('Presses, stitchers, skivers, line equipment.'), 'count' => '—'],
            ['key' => 'production', 'abbr' => 'PR', 'title' => __('Production'), 'description' => __('Line balance, WIP, throughput, operations.'), 'count' => '—'],
            ['key' => 'finishing', 'abbr' => 'FN', 'title' => __('Finishing'), 'description' => __('Polish, creams, burnish, protection.'), 'count' => '—'],
            ['key' => 'hardware', 'abbr' => 'HW', 'title' => __('Hardware'), 'description' => __('Eyelets, rivets, zips, metal parts.'), 'count' => '—'],
            ['key' => 'quality-control', 'abbr' => 'QC', 'title' => __('Quality control'), 'description' => __('Inspection, tolerances, defect codes.'), 'count' => '—'],
        ];
    }
}; ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <header class="max-w-2xl">
        <h2 id="domains-heading" class="text-sm font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
            {{ __('Industrial domains') }}
        </h2>
        <p class="mt-1 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
            {{ __('Structured vocabulary by production area. Term counts will reflect your published glossary.') }}
        </p>
    </header>

    <div class="mt-7 grid gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($this->domains as $domain)
            <article
                wire:key="domain-{{ $domain['key'] }}"
                class="group flex min-h-[9.5rem] flex-col rounded-lg border border-zinc-200/90 bg-zinc-50/50 p-4 transition-colors hover:border-zinc-300 hover:bg-white dark:border-zinc-800 dark:bg-zinc-900/30 dark:hover:border-zinc-600 dark:hover:bg-zinc-900/70"
            >
                <div class="flex items-start justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-2.5">
                        <span
                            class="flex min-h-8 min-w-8 shrink-0 items-center justify-center rounded border border-zinc-200 bg-white px-1 font-mono text-[10px] font-semibold leading-none tracking-tight text-zinc-600 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-400"
                            aria-hidden="true"
                        >
                            {{ $domain['abbr'] }}
                        </span>
                        <h3 class="truncate text-[13px] font-semibold leading-snug text-zinc-900 dark:text-zinc-100">
                            {{ $domain['title'] }}
                        </h3>
                    </div>
                    <span
                        class="shrink-0 text-end text-[11px] font-medium tabular-nums text-zinc-500 dark:text-zinc-500"
                        title="{{ __('Published concepts (placeholder)') }}"
                    >
                        <span class="sr-only">{{ __('Concept count') }}:</span>
                        {{ $domain['count'] }}
                    </span>
                </div>
                <p class="mt-2.5 flex-1 text-[12px] leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ $domain['description'] }}
                </p>
                <a
                    href="#"
                    class="mt-2 inline-flex items-center gap-1 text-[12px] font-medium text-zinc-700 underline decoration-zinc-300 underline-offset-4 transition-colors hover:text-zinc-900 hover:decoration-zinc-500 dark:text-zinc-300 dark:decoration-zinc-600 dark:hover:text-zinc-100 dark:hover:decoration-zinc-400"
                >
                    {{ __('View domain') }}
                    <span class="transition-transform group-hover:translate-x-px" aria-hidden="true">→</span>
                </a>
            </article>
        @endforeach
    </div>
</div>
