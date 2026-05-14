<?php

use App\Models\Domain;
use App\Models\Language;
use App\Support\Locales;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public ?int $languageId = null;

    public function mount(): void
    {
        $this->languageId = Language::activeIdForCode(Locales::current());
    }

    #[Computed]
    public function roots()
    {
        if ($this->languageId === null) {
            return collect();
        }

        return Domain::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with(['translations' => fn ($q) => $q->where('language_id', $this->languageId)])
            ->withCount([
                'concepts as terms_count' => function ($q): void {
                    $q->where('concepts.status', 'published')
                        ->whereHas('translations', fn ($t) => $t->where('language_id', $this->languageId));
                },
            ])
            ->orderBy('sort_order')
            ->get();
    }
}; ?>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <header class="max-w-2xl">
        <h2 id="domains-heading" class="text-xs font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
            {{ __('Industrial domains') }}
        </h2>
        <p class="mt-1 text-[12px] leading-relaxed text-zinc-600 dark:text-zinc-400">
            {{ __('Structured vocabulary by production area. Follow a domain for published glossary concepts in your language.') }}
        </p>
    </header>

    <div class="mt-5 grid gap-2 sm:grid-cols-2 sm:gap-2.5 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($this->roots as $domain)
            @php
                $tr = $domain->translations->first();
            @endphp
            @if ($tr !== null)
                <article
                    wire:key="domain-card-{{ $domain->id }}"
                    class="group flex flex-col rounded-lg border border-zinc-200/90 bg-white p-3 shadow-[0_1px_0_rgba(9,9,11,0.04)] transition-[border-color,box-shadow,background-color] duration-150 hover:border-zinc-300 hover:bg-zinc-50/80 hover:shadow-[0_2px_10px_rgba(9,9,11,0.05)] dark:border-zinc-800 dark:bg-zinc-900/35 dark:shadow-[0_1px_0_rgba(0,0,0,0.2)] dark:hover:border-zinc-600 dark:hover:bg-zinc-900/70 dark:hover:shadow-[0_2px_12px_rgba(0,0,0,0.35)]"
                >
                    <div class="flex items-start gap-2.5">
                        <span
                            class="flex size-7 shrink-0 items-center justify-center rounded-md border border-zinc-200 bg-zinc-50 font-mono text-[9px] font-semibold leading-none tracking-tight text-zinc-700 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-300"
                            aria-hidden="true"
                        >
                            {{ strtoupper(mb_substr($tr->slug, 0, 2, 'UTF-8')) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-[13px] font-semibold leading-tight tracking-tight text-zinc-900 dark:text-zinc-50">
                                {{ $tr->name }}
                            </h3>
                            <p class="mt-1 text-[11px] font-medium tabular-nums text-zinc-500 dark:text-zinc-400">
                                {{ __('Terms') }} ·
                                <span class="text-zinc-600 dark:text-zinc-300">
                                    @if ((int) $domain->terms_count === 0)
                                        {{ __('—') }}
                                    @else
                                        {{ $domain->terms_count }}
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
                    @if (filled($tr->description))
                        <p class="mt-2 line-clamp-2 text-[12px] leading-snug text-zinc-600 dark:text-zinc-400">
                            {{ $tr->description }}
                        </p>
                    @endif
                    <a
                        href="{{ route('domains.show', ['locale' => Locales::current(), 'slug' => $tr->slug]) }}"
                        wire:navigate
                        class="mt-2.5 inline-flex items-center gap-1 self-start text-[12px] font-medium text-zinc-800 transition-colors hover:text-zinc-950 dark:text-zinc-200 dark:hover:text-white"
                    >
                        {{ __('Explore') }}
                        <span class="text-zinc-400 transition-transform duration-150 group-hover:translate-x-0.5 dark:text-zinc-500" aria-hidden="true">→</span>
                    </a>
                </article>
            @endif
        @empty
            <p class="col-span-full text-[13px] text-zinc-600 dark:text-zinc-400" role="status">
                {{ __('No domains are configured yet.') }}
            </p>
        @endforelse
    </div>
</div>
