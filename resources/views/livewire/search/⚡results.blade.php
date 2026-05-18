<?php

use App\Models\Domain;
use App\Models\Language;
use App\Support\Editorial\WorkflowStatus;
use App\Support\GlossarySearch;
use App\Support\Locales;
use App\Support\Search\GlossarySearchFilters;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    private const int MIN_QUERY_LENGTH = 2;

    #[Locked]
    public string $locale = '';

    #[Url(as: 'q', history: true)]
    public string $q = '';

    #[Url(as: GlossarySearchFilters::QUERY_DOMAIN, history: true)]
    public string $domain = '';

    #[Url(as: GlossarySearchFilters::QUERY_LOCALE, history: true)]
    public string $filterLocale = '';

    #[Url(as: GlossarySearchFilters::QUERY_STATUS, history: true)]
    public string $publicationStatus = '';

    #[Url(as: GlossarySearchFilters::QUERY_RELATION, history: true)]
    public string $relationType = '';

    public ?int $languageId = null;

    public bool $editorialContext = false;

    /**
     * @var list<array{slug: string, label: string}>
     */
    public array $domainOptions = [];

    /**
     * @var list<array{code: string, label: string}>
     */
    public array $localeOptions = [];

    public function mount(string $locale): void
    {
        $this->locale = $locale;
        $this->languageId = Language::activeIdForCode($this->locale);
        $this->editorialContext = request()->user() !== null;

        $parsed = GlossarySearchFilters::fromQuery(request()->query(), $this->locale, $this->editorialContext);
        $this->domain = $parsed->domainSlug ?? '';
        $this->filterLocale = $parsed->localeCode ?? '';
        $this->publicationStatus = $parsed->publicationStatus ?? '';
        $this->relationType = $parsed->relationType ?? '';

        $this->localeOptions = collect(Locales::supported())
            ->map(fn (array $meta, string $code): array => ['code' => $code, 'label' => $meta['native']])
            ->values()
            ->all();

        $this->domainOptions = $this->resolveDomainOptions();
    }

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    public function updatingDomain(): void
    {
        $this->resetPage();
    }

    public function updatingFilterLocale(): void
    {
        $this->resetPage();
    }

    public function updatedFilterLocale(): void
    {
        $this->domainOptions = $this->resolveDomainOptions();
    }

    public function updatingPublicationStatus(): void
    {
        $this->resetPage();
    }

    public function updatingRelationType(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->domain = '';
        $this->filterLocale = '';
        $this->publicationStatus = '';
        $this->relationType = '';
        $this->resetPage();
    }

    protected function needle(): string
    {
        return mb_substr(trim($this->q), 0, 200);
    }

    protected function filters(): GlossarySearchFilters
    {
        return GlossarySearchFilters::fromQuery([
            GlossarySearchFilters::QUERY_DOMAIN => $this->domain,
            GlossarySearchFilters::QUERY_LOCALE => $this->filterLocale,
            GlossarySearchFilters::QUERY_STATUS => $this->publicationStatus,
            GlossarySearchFilters::QUERY_RELATION => $this->relationType,
        ], $this->locale, $this->editorialContext);
    }

    public function getResultsProperty(): LengthAwarePaginator
    {
        $needle = $this->needle();
        $filters = $this->filters();

        if (mb_strlen($needle) < self::MIN_QUERY_LENGTH) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
                'path' => request()->url(),
                'pageName' => 'page',
                'query' => array_filter(array_merge(['q' => $needle], $filters->toQuery())),
            ]);
        }

        return GlossarySearch::paginate($this->locale, $needle, 15, $filters);
    }

    /**
     * @return list<array{slug: string, label: string}>
     */
    private function resolveDomainOptions(): array
    {
        $localeForLabels = $this->filterLocale !== '' && Locales::isSupported($this->filterLocale)
            ? $this->filterLocale
            : $this->locale;
        $languageId = Language::activeIdForCode($localeForLabels);

        $domains = Domain::query()
            ->where('is_active', true)
            ->with(['translations' => fn ($q) => $q
                ->select(['id', 'domain_id', 'language_id', 'name'])
                ->when($languageId !== null, fn ($t) => $t->where('language_id', $languageId)),
            ])
            ->orderBy('sort_order')
            ->orderBy('slug')
            ->get(['id', 'slug', 'sort_order']);

        return $domains->map(function (Domain $domain): array {
            $label = $domain->translations->first()?->name ?? $domain->slug;

            return [
                'slug' => (string) $domain->slug,
                'label' => (string) $label,
            ];
        })->all();
    }
}; ?>

@php
    $needle = $this->needle();
    $activeFilters = $this->filters();
    $hasActiveFilters = $activeFilters->toQuery() !== [];
    $statusLabels = [
        WorkflowStatus::DRAFT => __('admin.draft'),
        WorkflowStatus::REVIEW => __('admin.in_review'),
        WorkflowStatus::PUBLISHED => __('admin.published'),
        WorkflowStatus::ARCHIVED => __('admin.archived'),
    ];
    $relationLabels = [
        'synonym' => __('messages.semantic_synonyms'),
        'broader' => __('messages.semantic_broader'),
        'narrower' => __('messages.semantic_narrower'),
        'related' => __('messages.semantic_related'),
    ];
@endphp

<div class="space-y-8">
    <div class="space-y-4">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                {{ __('ui.search_glossary') }}
            </p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                {{ __('ui.search_results') }}
            </h1>
        </div>

        <div
            class="rounded-2xl border border-zinc-200 bg-white p-1.5 shadow-sm ring-1 ring-zinc-950/[0.03] transition-[border-color,box-shadow] duration-150 focus-within:border-zinc-300 focus-within:shadow-md dark:border-zinc-600/90 dark:bg-zinc-900 dark:ring-white/[0.04] dark:focus-within:border-zinc-500 sm:p-2 [&_input]:h-11 [&_input]:min-h-0 [&_input]:rounded-xl [&_input]:border-0 [&_input]:bg-transparent [&_input]:px-3 [&_input]:text-[14px] [&_input]:font-normal [&_input]:text-zinc-900 [&_input]:shadow-none [&_input]:ring-0 dark:[&_input]:text-zinc-100 [&_input]:focus:ring-0"
        >
            <flux:input
                id="results-search-input"
                data-search-focus="results"
                wire:model.live.debounce.450ms="q"
                type="search"
                :label="__('ui.refine_query')"
                :placeholder="__('ui.search_placeholder')"
                icon="magnifying-glass"
                autocomplete="off"
            />
        </div>
        <p class="text-right text-[11px] text-zinc-500 dark:text-zinc-400" aria-hidden="true">
            {{ __('ui.search') }}: <kbd class="rounded border border-zinc-300 px-1 py-0.5 text-[10px] font-semibold dark:border-zinc-600">/</kbd>
        </p>

        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
            <flux:select wire:model.live="domain" :label="__('ui.domains')">
                <option value="">{{ __('admin.all') }}</option>
                @foreach ($domainOptions as $option)
                    <option value="{{ $option['slug'] }}">{{ $option['label'] }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="filterLocale" :label="__('ui.language')">
                <option value="">{{ __('admin.all') }}</option>
                @foreach ($localeOptions as $option)
                    <option value="{{ $option['code'] }}">{{ $option['label'] }}</option>
                @endforeach
            </flux:select>

            @if ($editorialContext)
                <flux:select wire:model.live="publicationStatus" :label="__('admin.translation_status')">
                    <option value="">{{ __('admin.all') }}</option>
                    <option value="{{ WorkflowStatus::DRAFT }}">{{ __('admin.draft') }}</option>
                    <option value="{{ WorkflowStatus::REVIEW }}">{{ __('admin.in_review') }}</option>
                    <option value="{{ WorkflowStatus::PUBLISHED }}">{{ __('admin.published') }}</option>
                    <option value="{{ WorkflowStatus::ARCHIVED }}">{{ __('admin.archived') }}</option>
                </flux:select>
            @endif

            <flux:select wire:model.live="relationType" :label="__('admin.relations')">
                <option value="">{{ __('admin.all') }}</option>
                <option value="synonym">{{ __('messages.semantic_synonyms') }}</option>
                <option value="broader">{{ __('messages.semantic_broader') }}</option>
                <option value="narrower">{{ __('messages.semantic_narrower') }}</option>
                <option value="related">{{ __('messages.semantic_related') }}</option>
            </flux:select>
        </div>

        @if ($hasActiveFilters)
            <div class="flex items-center justify-between">
                <p class="text-[12px] text-zinc-500 dark:text-zinc-400">
                    {{ __('admin.apply_filters') }}
                </p>
                <flux:button size="xs" variant="ghost" wire:click="clearFilters">
                    {{ __('admin.reset') }}
                </flux:button>
            </div>
        @endif
    </div>

    @if (mb_strlen($needle) < 2)
        @include('partials.ui.empty-state', [
            'title' => __('ui.search_empty_prompt'),
            'message' => \App\Support\GlossaryScoutQuery::usesMeilisearch() ? __('ui.search_empty_meili') : __('ui.search_empty_sql'),
        ])
    @elseif ($this->results->isEmpty())
        @include('partials.ui.empty-state', [
            'title' => __('ui.search_result_empty', ['q' => $needle]),
            'message' => $hasActiveFilters ? __('admin.no_gaps_for_filter') : __('ui.search_try_shorter'),
        ])
    @else
        <section aria-labelledby="search-results-heading" class="space-y-4">
            <div wire:loading.delay class="rounded-md border border-zinc-200 bg-zinc-50 px-3 py-2 text-[12px] text-zinc-600 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300">
                {{ __('ui.search') }}...
            </div>
            <div class="flex flex-wrap items-end justify-between gap-3 border-b border-zinc-200/90 pb-4 dark:border-zinc-800">
                <div class="min-w-0">
                    <p id="search-results-heading" class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                        {{ __('ui.search_query_label') }}
                    </p>
                    <p class="mt-1 truncate text-[15px] font-semibold text-zinc-900 dark:text-zinc-50" title="{{ $needle }}">
                        {{ $needle }}
                    </p>
                </div>
                <p class="text-[12px] tabular-nums text-zinc-500 dark:text-zinc-400">
                    @if ($this->results->total() === 1)
                        {{ __('ui.result_count_one') }}
                    @else
                        {{ __('ui.result_count_many', ['count' => $this->results->total()]) }}
                    @endif
                </p>
            </div>

            <ul class="space-y-3" role="list">
                @foreach ($this->results as $row)
                    @php
                        $snippet = $row->scoutHighlightedSnippet($needle);
                        $snippetVisible = trim(strip_tags((string) $snippet)) !== '';
                        $returnQuery = array_filter([
                            'rq' => $needle,
                            'rd' => $activeFilters->domainSlug,
                            'rl' => $activeFilters->localeCode,
                            'rs' => $activeFilters->publicationStatus,
                            'rr' => $activeFilters->relationType,
                        ], static fn ($v): bool => is_string($v) && $v !== '');
                        $targetUrl = $row->glossaryUrl().($returnQuery === [] ? '' : '?'.http_build_query($returnQuery));
                    @endphp
                    <li wire:key="search-hit-{{ $row->id }}" class="rounded-lg border border-zinc-200/90 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-900/40">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0 flex-1 space-y-1.5">
                                <h2 class="text-[15px] font-semibold leading-snug text-zinc-900 dark:text-zinc-50">
                                    <a
                                        href="{{ $targetUrl }}"
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

                                <div class="flex flex-wrap items-center gap-1.5 text-[11px]">
                                    @if ($row->language?->code)
                                        @include('partials.ui.locale-indicator', ['code' => $row->language->code])
                                    @endif

                                    @if ($activeFilters->relationType !== null)
                                        @include('partials.ui.status-badge', [
                                            'label' => $relationLabels[$activeFilters->relationType] ?? __('messages.semantic_related'),
                                            'status' => 'semantic',
                                        ])
                                    @elseif (($row->concept?->outgoing_relations_count ?? 0) + ($row->concept?->incoming_relations_count ?? 0) > 0)
                                        @include('partials.ui.status-badge', ['label' => __('learning.semantic'), 'status' => 'semantic'])
                                    @endif

                                    @if ($editorialContext && $row->status)
                                        @include('partials.ui.status-badge', [
                                            'label' => $statusLabels[$row->status] ?? $row->status,
                                            'status' => $row->status,
                                        ])
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($row->concept?->domains->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-1.5 border-t border-zinc-200/80 pt-2 dark:border-zinc-800">
                                @foreach ($row->concept->domains as $domain)
                                    @php
                                        $label = $this->languageId !== null
                                            ? ($domain->translations->firstWhere('language_id', $this->languageId)?->name ?? $domain->slug)
                                            : $domain->slug;
                                    @endphp
                                    <span wire:key="sd-{{ $row->id }}-{{ $domain->id }}">
                                        @include('partials.ui.semantic-chip', ['label' => $label, 'interactive' => false])
                                    </span>
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
