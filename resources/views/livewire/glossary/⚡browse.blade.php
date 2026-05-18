<?php

use App\Models\ConceptTranslation;
use App\Models\Language;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    /** @var list<string> */
    public array $alphabet;

    #[Locked]
    public string $letter;

    #[Locked]
    public string $locale;

    public ?int $languageId = null;

    public function mount(string $letter, string $locale): void
    {
        $this->letter = mb_strtoupper(mb_substr($letter, 0, 1, 'UTF-8'), 'UTF-8');
        $this->locale = $locale;
        $this->alphabet = range('A', 'Z');
        $this->languageId = Language::activeIdForCode($this->locale);
    }

    public function getTermsProperty(): LengthAwarePaginator
    {
        $query = ConceptTranslation::query()
            ->forGlossaryLetter($this->locale, $this->letter)
            ->orderBy('term');

        if ($this->languageId !== null) {
            $query->with([
                'concept.domains.translations' => fn ($q) => $q->where('language_id', $this->languageId),
            ]);
        } else {
            $query->with(['concept.domains.translations']);
        }

        return $query->paginate(15);
    }
}; ?>

<div class="space-y-8">
    <nav class="space-y-2" aria-label="{{ __('search.glossary_alphabet') }}">
        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
            {{ __('ui.browse_by_letter') }}
        </p>
        <div class="flex flex-wrap gap-1.5">
            @foreach ($alphabet as $l)
                @php
                    $active = $l === $letter;
                    $href = route('glossary.letter', ['locale' => $locale, 'letter' => strtolower($l)]);
                @endphp
                <a
                    wire:key="letter-{{ $l }}"
                    href="{{ $href }}"
                    @class([
                        'inline-flex min-w-8 items-center justify-center rounded-md border px-2 py-1 text-[12px] font-semibold tabular-nums transition-colors',
                        'border-zinc-900 bg-zinc-900 text-white dark:border-zinc-100 dark:bg-zinc-100 dark:text-zinc-900' => $active,
                        'border-zinc-200 bg-white text-zinc-700 hover:border-zinc-300 hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900/40 dark:text-zinc-200 dark:hover:border-zinc-500 dark:hover:bg-zinc-900' => ! $active,
                    ])
                    @if ($active) aria-current="page" @endif
                >
                    {{ $l }}
                </a>
            @endforeach
        </div>
    </nav>

    <section aria-labelledby="glossary-letter-heading" class="space-y-4">
        <div class="flex flex-wrap items-end justify-between gap-3 border-b border-zinc-200/90 pb-4 dark:border-zinc-800">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                    {{ __('search.current_letter') }}
                </p>
                <h2 id="glossary-letter-heading" class="mt-1 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                    {{ $letter }}
                </h2>
            </div>
            <p class="text-[12px] tabular-nums text-zinc-500 dark:text-zinc-400">
                @if ($this->terms->total() === 1)
                    {{ __('search.one_term') }}
                @else
                    {{ __('search.count_terms', ['count' => $this->terms->total()]) }}
                @endif
            </p>
        </div>

        @if ($this->terms->isEmpty())
            @include('partials.ui.empty-state', ['message' => __('search.no_terms_for_letter')])
        @else
            <ul class="divide-y divide-zinc-200/90 dark:divide-zinc-800" role="list">
                @foreach ($this->terms as $row)
                    <li wire:key="term-{{ $row->id }}" class="py-4 first:pt-0">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0 flex-1 space-y-1">
                                <h3 class="text-[15px] font-semibold leading-snug text-zinc-900 dark:text-zinc-50">
                                    <a
                                        href="{{ route('glossary.concept', ['locale' => $locale, 'slug' => $row->slug]) }}"
                                        wire:navigate
                                        class="hover:underline decoration-zinc-400 underline-offset-2"
                                    >
                                        {{ $row->term }}
                                    </a>
                                </h3>
                                @if (filled($row->short_definition))
                                    <p class="text-[13px] leading-relaxed text-zinc-600 dark:text-zinc-400">
                                        {{ \Illuminate\Support\Str::limit($row->short_definition, 220) }}
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
                                    <span wire:key="d-{{ $row->id }}-{{ $domain->id }}">
                                        @include('partials.ui.semantic-chip', ['label' => $label, 'interactive' => false])
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>

            @if ($this->terms->hasPages())
                <div class="pt-2">
                    {{ $this->terms->onEachSide(1)->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        @endif
    </section>
</div>
