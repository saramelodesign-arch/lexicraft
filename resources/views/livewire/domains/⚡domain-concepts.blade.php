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

    #[Locked]
    public int $domainId = 0;

    #[Locked]
    public string $locale = '';

    public ?int $languageId = null;

    public function mount(int $domainId, string $locale): void
    {
        $this->domainId = $domainId;
        $this->locale = $locale;
        $this->languageId = Language::activeIdForCode($this->locale);
    }

    public function getConceptsProperty(): LengthAwarePaginator
    {
        return ConceptTranslation::query()
            ->forPublishedLocale($this->locale)
            ->whereHas('concept.domains', fn ($q) => $q->where('domains.id', $this->domainId))
            ->with([
                'concept.domains.translations' => fn ($q) => $q->where('language_id', $this->languageId),
            ])
            ->orderBy('term')
            ->paginate(15)
            ->withQueryString();
    }
}; ?>

<div class="space-y-4">
    @if ($this->languageId === null)
        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('search.language_not_available') }}</p>
    @elseif ($this->concepts->isEmpty())
        @include('partials.ui.empty-state', ['message' => __('search.no_published_concepts_for_domain')])
    @else
        <ul class="divide-y divide-zinc-200/90 dark:divide-zinc-800" role="list">
            @foreach ($this->concepts as $row)
                <li wire:key="dc-{{ $row->id }}" class="py-4 first:pt-0">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0 flex-1 space-y-1">
                            <h3 class="text-[15px] font-semibold leading-snug text-zinc-900 dark:text-zinc-50">
                                <a
                                    href="{{ $row->glossaryUrl() }}"
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
                                <div class="flex flex-wrap gap-1.5">
                                    @if ($row->language?->code)
                                        @include('partials.ui.locale-indicator', ['code' => $row->language->code])
                                    @endif
                                    @include('partials.ui.status-badge', ['label' => __('admin.published'), 'status' => 'published'])
                                </div>
                        </div>
                    </div>
                    @if ($row->concept?->domains->isNotEmpty())
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach ($row->concept->domains as $d)
                                @php
                                    $dlabel = $this->languageId !== null
                                        ? ($d->translations->firstWhere('language_id', $this->languageId)?->name ?? $d->slug)
                                        : $d->slug;
                                    $dtr = $this->languageId !== null ? $d->translations->firstWhere('language_id', $this->languageId) : null;
                                @endphp
                                @if ($dtr !== null && $dtr->slug !== '')
                                    <span wire:key="dc-d-{{ $row->id }}-{{ $d->id }}">
                                        @include('partials.ui.semantic-chip', [
                                            'label' => $dlabel,
                                            'href' => route('domains.show', ['locale' => $this->locale, 'slug' => $dtr->slug]),
                                        ])
                                    </span>
                                @else
                                    <span wire:key="dc-d-{{ $row->id }}-{{ $d->id }}">
                                        @include('partials.ui.semantic-chip', ['label' => $dlabel, 'interactive' => false])
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>

        @if ($this->concepts->hasPages())
            <div class="pt-2">
                {{ $this->concepts->onEachSide(1)->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    @endif
</div>
