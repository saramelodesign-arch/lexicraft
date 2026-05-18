@php
    use App\Support\Editorial\WorkflowStatus;
    use App\Support\Locales;

    $firstLetter = mb_strtoupper(mb_substr($translation->term, 0, 1, 'UTF-8'), 'UTF-8');
    $letterRoute = route('glossary.letter', ['locale' => $locale, 'letter' => strtolower($firstLetter)], absolute: false);
    $localeSwitch = collect(Locales::codes())
        ->map(fn (string $code): array => [
            'code' => $code,
            'url' => $alternates[$code] ?? null,
        ])
        ->filter(fn (array $item): bool => is_string($item['url']))
        ->values();
    $searchReturnQuery = array_filter([
        'q' => request()->query('rq'),
        'domain' => request()->query('rd'),
        'lang' => request()->query('rl'),
        'status' => request()->query('rs'),
        'rel' => request()->query('rr'),
    ], static fn ($v): bool => is_string($v) && $v !== '');
    $searchReturnUrl = $searchReturnQuery !== []
        ? route('search', array_merge(['locale' => $locale], $searchReturnQuery))
        : null;

    $localeSwitch = $localeSwitch
        ->map(function (array $item) use ($searchReturnQuery): array {
            if ($searchReturnQuery === [] || ! is_string($item['url'])) {
                return $item;
            }

            $item['url'] = $item['url'].'?'.http_build_query(array_merge([
                'rq' => $searchReturnQuery['q'] ?? '',
                'rd' => $searchReturnQuery['domain'] ?? '',
                'rl' => $searchReturnQuery['lang'] ?? '',
                'rs' => $searchReturnQuery['status'] ?? '',
                'rr' => $searchReturnQuery['rel'] ?? '',
            ], []));

            return $item;
        })
        ->values();
    $availableLocaleCodes = $localeSwitch->pluck('code')->all();
    $missingLocaleCodes = array_values(array_diff(Locales::codes(), $availableLocaleCodes));
    $availableLocaleCount = count($availableLocaleCodes);
    $totalLocaleCount = count(Locales::codes());
    $conceptStatusLabel = match ($concept->status) {
        WorkflowStatus::DRAFT => __('admin.draft'),
        WorkflowStatus::REVIEW => __('admin.in_review'),
        WorkflowStatus::ARCHIVED => __('admin.archived'),
        default => __('admin.published'),
    };
    $translationStatusLabel = match ($translation->status) {
        WorkflowStatus::DRAFT => __('admin.draft'),
        WorkflowStatus::REVIEW => __('admin.in_review'),
        WorkflowStatus::ARCHIVED => __('admin.archived'),
        default => __('admin.published'),
    };
    $terminologyStatusLabel = __('admin.terminology_status_'.($translation->terminology_status ?: 'draft'));
    $validationStateLabel = filled($translation->validated_at) && filled($translation->validator?->name)
        ? __('admin.validation_state_validated')
        : __('admin.validation_state_not_validated');
    $validatedAtLabel = $translation->validated_at?->locale(app()->getLocale())->isoFormat('LLL');
    $confidenceLevelLabel = __('admin.confidence_'.$confidenceLevel);
    $hasProcessContext = filled($translation->industry_notes);
    $domainNodes = $concept->domains->sortBy('id')->values();
    $primaryDomain = $domainNodes->first();
    $primaryDomainLabel = null;
    $primaryDomainUrl = null;
    $subdomainLabel = null;
    $subdomainUrl = null;
    $relatedDomainNodes = collect();

    if ($primaryDomain !== null) {
        $primaryTranslation = $primaryDomain->translations->firstWhere('language_id', $languageId);
        if ($primaryTranslation !== null && filled($primaryTranslation->slug)) {
            $primaryDomainLabel = $primaryTranslation->name;
            $primaryDomainUrl = route('domains.show', ['locale' => $locale, 'slug' => $primaryTranslation->slug]);
        } else {
            $primaryDomainLabel = $primaryDomain->slug;
        }

        $parentDomain = $primaryDomain->parent;
        if ($parentDomain !== null) {
            $parentTranslation = $parentDomain->translations->firstWhere('language_id', $languageId);
            if ($parentTranslation !== null && filled($parentTranslation->slug)) {
                $primaryDomainLabel = $parentTranslation->name;
                $primaryDomainUrl = route('domains.show', ['locale' => $locale, 'slug' => $parentTranslation->slug]);
            } elseif (filled($parentDomain->slug)) {
                $primaryDomainLabel = $parentDomain->slug;
            }

            if ($primaryTranslation !== null && filled($primaryTranslation->name)) {
                $subdomainLabel = $primaryTranslation->name;
                $subdomainUrl = filled($primaryTranslation->slug)
                    ? route('domains.show', ['locale' => $locale, 'slug' => $primaryTranslation->slug])
                    : null;
            } else {
                $subdomainLabel = $primaryDomain->slug;
            }
        }
    }

    $relatedDomainNodes = $domainNodes
        ->filter(fn ($domain) => $domain->id !== ($primaryDomain?->id ?? 0))
        ->values();
@endphp

<x-layouts::site
    :title="$pageTitle"
    :meta-description="$metaDescription"
    :canonical="$canonical"
    :og-url="$canonical"
    :og-title="$ogTitle"
    :og-description="$ogDescription"
    og-type="article"
    :structured-data="null"
    :json-ld-blocks="$jsonLdBlocks"
    :og-image="$ogImage"
    :og-image-alt="$ogImageAlt"
>
    @push('meta')
        @include('partials.hreflang-alternates', ['alternates' => $alternates, 'xDefaultUrl' => $xDefaultUrl])
    @endpush

    <livewire:layout.header />

    <main id="content" class="flex-1 px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-5xl space-y-8">
            <div class="space-y-3">
                <flux:link :href="route('home', ['locale' => $locale])" wire:navigate variant="subtle" class="text-[13px] text-zinc-600 dark:text-zinc-400">
                    ← {{ __('ui.home_back') }}
                </flux:link>
                @isset($breadcrumbs)
                    @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                @endisset
                @if ($searchReturnUrl !== null)
                    <flux:link :href="$searchReturnUrl" wire:navigate variant="subtle" class="block text-[13px] text-zinc-600 dark:text-zinc-400">
                        ← {{ __('ui.search_results') }}
                    </flux:link>
                @endif
                <flux:link :href="$letterRoute" wire:navigate variant="subtle" class="block text-[13px] text-zinc-600 dark:text-zinc-400">
                    ← {{ __('search.glossary_letter_with_letter', ['letter' => $firstLetter]) }}
                </flux:link>
            </div>

            <article class="space-y-8">
                <section class="rounded-xl border border-zinc-200/90 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900/40" aria-labelledby="concept-summary-heading">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="space-y-2">
                            <p id="concept-summary-heading" class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                                {{ __('search.concept') }}
                            </p>
                            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                                {{ $translation->term }}
                            </h1>
                            @if (filled($translation->short_definition))
                                <p class="max-w-3xl text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">
                                    {{ $translation->short_definition }}
                                </p>
                            @endif
                        </div>

                        <nav aria-label="{{ __('search.other_languages') }}" class="space-y-2 lg:w-[18rem]">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                                {{ __('search.this_concept_other_languages') }}
                            </p>
                            <ul class="flex flex-wrap gap-2">
                                @foreach ($localeSwitch as $item)
                                    <li>
                                        <a
                                            href="{{ $item['url'] }}"
                                            data-locale-code="{{ $item['code'] }}"
                                            data-locale-switch
                                            @class([
                                                'rounded-md border px-2 py-1 text-[12px] font-medium',
                                                'border-zinc-900 bg-zinc-900 text-white dark:border-zinc-100 dark:bg-zinc-100 dark:text-zinc-900' => $item['code'] === $locale,
                                                'border-zinc-200 text-zinc-700 hover:border-zinc-300 dark:border-zinc-700 dark:text-zinc-200 dark:hover:border-zinc-500' => $item['code'] !== $locale,
                                            ])
                                            @if ($item['code'] === $locale) aria-current="page" @endif
                                        >
                                            @include('partials.ui.locale-indicator', ['code' => $item['code'], 'active' => $item['code'] === $locale])
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>

                    <div class="mt-4 grid gap-4 lg:grid-cols-[1.15fr_0.85fr]">
                        <div class="space-y-3">
                            <div class="flex flex-wrap gap-1.5">
                                @include('partials.ui.semantic-chip', ['label' => __('ui.language').':', 'interactive' => false])
                                @include('partials.ui.locale-indicator', ['code' => $locale, 'active' => true])
                                @include('partials.ui.status-badge', ['label' => __('messages.semantic_entry_status').': '.$translationStatusLabel, 'status' => $translation->status])
                                @include('partials.ui.status-badge', ['label' => __('admin.terminology_status').': '.$terminologyStatusLabel, 'status' => $translation->terminology_status ?: 'draft'])
                                @include('partials.ui.status-badge', ['label' => __('admin.workflow_status').': '.$conceptStatusLabel, 'status' => $concept->status])
                                @include('partials.ui.semantic-chip', ['label' => __('admin.published_locales').': '.$availableLocaleCount.'/'.$totalLocaleCount, 'interactive' => false])
                                @if ($concept->is_featured)
                                    @include('partials.ui.semantic-chip', ['label' => __('admin.featured_concept'), 'interactive' => false])
                                @endif
                            </div>
                            @if ($missingLocaleCodes !== [])
                                <div class="space-y-1.5">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-400">{{ __('admin.missing_locales') }}</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($missingLocaleCodes as $missingCode)
                                            @include('partials.ui.locale-indicator', ['code' => $missingCode, 'active' => false])
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($concept->domains->isNotEmpty())
                                <div class="space-y-1.5">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-400">
                                        {{ __('search.industrial_domains') }}
                                    </p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach ($concept->domains as $domain)
                                            @php
                                                $dt = $domain->translations->firstWhere('language_id', $languageId);
                                                $dlabel = $dt?->name ?? $domain->slug;
                                            @endphp
                                            @if ($dt !== null && filled($dt->slug))
                                                @include('partials.ui.semantic-chip', [
                                                    'label' => $dlabel,
                                                    'href' => route('domains.show', ['locale' => $locale, 'slug' => $dt->slug]),
                                                ])
                                            @else
                                                @include('partials.ui.semantic-chip', ['label' => $dlabel, 'interactive' => false])
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-2 rounded-lg border border-zinc-200/90 bg-zinc-50/80 p-3 dark:border-zinc-800 dark:bg-zinc-900/40">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-400">
                                {{ __('messages.domain_context_panel') }}
                            </p>
                            <ul class="space-y-1.5 text-[12px] text-zinc-700 dark:text-zinc-300">
                                <li>
                                    <span class="font-semibold">{{ __('search.industrial_domain') }}:</span>
                                    @if ($primaryDomainLabel !== null)
                                        @if ($primaryDomainUrl !== null)
                                            <a href="{{ $primaryDomainUrl }}" wire:navigate class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ $primaryDomainLabel }}</a>
                                        @else
                                            {{ $primaryDomainLabel }}
                                        @endif
                                    @else
                                        —
                                    @endif
                                </li>
                                <li>
                                    <span class="font-semibold">{{ __('search.sub_domains') }}:</span>
                                    @if ($subdomainLabel !== null)
                                        @if ($subdomainUrl !== null)
                                            <a href="{{ $subdomainUrl }}" wire:navigate class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ $subdomainLabel }}</a>
                                        @else
                                            {{ $subdomainLabel }}
                                        @endif
                                    @else
                                        {{ __('messages.semantic_no_subdomain') }}
                                    @endif
                                </li>
                                <li>
                                    <span class="font-semibold">{{ __('search.related_domains') }}:</span>
                                    @if ($relatedDomainNodes->isNotEmpty())
                                        @foreach ($relatedDomainNodes as $domain)
                                            @php($dt = $domain->translations->firstWhere('language_id', $languageId))
                                            @if ($dt !== null && filled($dt->slug))
                                                <a href="{{ route('domains.show', ['locale' => $locale, 'slug' => $dt->slug]) }}" wire:navigate class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">
                                                    {{ $dt->name }}
                                                </a>@if (! $loop->last), @endif
                                            @else
                                                {{ $domain->slug }}@if (! $loop->last), @endif
                                            @endif
                                        @endforeach
                                    @else
                                        {{ __('messages.semantic_no_related_areas') }}
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl border border-zinc-200/90 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900/40" aria-labelledby="concept-knowledge-heading">
                    <header class="mb-4">
                        <h2 id="concept-knowledge-heading" class="text-[13px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                            {{ __('learning.definition_context') }}
                        </h2>
                    </header>

                    @if (filled($translation->full_definition))
                        <section aria-labelledby="full-def-heading" class="space-y-2">
                            <h3 id="full-def-heading" class="text-[15px] font-semibold text-zinc-900 dark:text-zinc-50">
                                {{ __('search.definition') }}
                            </h3>
                            <div class="prose prose-sm max-w-none text-zinc-700 dark:prose-invert dark:text-zinc-300">
                                {!! nl2br(e($translation->full_definition)) !!}
                            </div>
                        </section>
                    @endif

                    @if ($hasProcessContext)
                        <section aria-labelledby="notes-heading" class="mt-5 space-y-2">
                            <h3 id="notes-heading" class="text-[15px] font-semibold text-zinc-900 dark:text-zinc-50">
                                {{ __('search.industry_notes') }}
                            </h3>
                            <p class="text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">
                                {{ $translation->industry_notes }}
                            </p>
                        </section>
                    @endif

                    @if ($translation->examples->isNotEmpty())
                        <section aria-labelledby="examples-heading" class="mt-5 space-y-3">
                            <h3 id="examples-heading" class="text-[15px] font-semibold text-zinc-900 dark:text-zinc-50">
                                {{ __('admin.examples') }}
                            </h3>
                            <ul class="space-y-3" role="list">
                                @foreach ($translation->examples as $ex)
                                    <li class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 p-3 text-sm dark:border-zinc-800 dark:bg-zinc-900/40">
                                        <p class="leading-relaxed text-zinc-800 dark:text-zinc-200">"{{ $ex->example }}"</p>
                                        @if (filled($ex->context))
                                            <div class="mt-1">
                                                @include('partials.ui.semantic-chip', ['label' => $ex->context, 'interactive' => false])
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    <div class="mt-6">
                        @include('partials.glossary-concept-media', [
                            'concept' => $concept,
                            'translation' => $translation,
                            'locale' => $locale,
                        ])
                    </div>

                    <div class="mt-6">
                        @include('partials.glossary-process-nav', [
                            'workflowNeighbors' => $workflowNeighbors,
                            'workflowSignals' => $workflowSignals,
                            'readingJourneys' => $readingJourneys,
                            'locale' => $locale,
                        ])
                        @include('partials.glossary-semantic-graph', [
                            'semanticGrouped' => $semanticGrouped,
                            'workflowNeighbors' => $workflowNeighbors,
                            'supersededBy' => $supersededBy,
                            'domainCoConcepts' => $domainCoConcepts,
                            'locale' => $locale,
                        ])
                    </div>
                </section>

                <section class="rounded-xl border border-zinc-200/90 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900/40" aria-labelledby="concept-trust-heading">
                    <header class="mb-4">
                        <h2 id="concept-trust-heading" class="text-[13px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                            {{ __('messages.semantic_entry_status') }}
                        </h2>
                    </header>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 px-3 py-2 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.workflow_status') }}</p>
                            <div class="mt-1">
                                @include('partials.ui.status-badge', ['label' => $conceptStatusLabel, 'status' => $concept->status, 'size' => 'md'])
                            </div>
                        </div>
                        <div class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 px-3 py-2 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.translation_status') }}</p>
                            <div class="mt-1">
                                @include('partials.ui.status-badge', ['label' => $translationStatusLabel, 'status' => $translation->status, 'size' => 'md'])
                            </div>
                        </div>
                        <div class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 px-3 py-2 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.terminology_status') }}</p>
                            <div class="mt-1">
                                @include('partials.ui.status-badge', ['label' => $terminologyStatusLabel, 'status' => $translation->terminology_status ?: 'draft', 'size' => 'md'])
                            </div>
                        </div>
                        <div class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 px-3 py-2 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.validation_state') }}</p>
                            <div class="mt-1">
                                @include('partials.ui.status-badge', ['label' => $validationStateLabel, 'status' => $translation->validated_at ? 'validated' : 'not_validated', 'size' => 'md'])
                            </div>
                        </div>
                        <div class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 px-3 py-2 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.validated_at') }}</p>
                            <p class="mt-1 text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $validatedAtLabel ?? '—' }}</p>
                        </div>
                        <div class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 px-3 py-2 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.validated_by') }}</p>
                            <p class="mt-1 text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $translation->validator?->name ?? '—' }}</p>
                        </div>
                        <div class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 px-3 py-2 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('ui.updated') }}</p>
                            <p class="mt-1 text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $translation->updated_at?->format('Y-m-d') ?? '—' }}</p>
                        </div>
                        <div class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 px-3 py-2 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">UUID</p>
                            <p class="mt-1 text-xs font-medium text-zinc-900 dark:text-zinc-100">{{ $concept->uuid }}</p>
                        </div>
                        <div class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 px-3 py-2 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('ui.language') }}</p>
                            <div class="mt-1">
                                @include('partials.ui.semantic-chip', ['label' => strtoupper($locale), 'interactive' => false])
                            </div>
                        </div>
                        <div class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 px-3 py-2 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.confidence_indicator') }}</p>
                            <div class="mt-1">
                                @include('partials.ui.status-badge', ['label' => $confidenceLevelLabel, 'status' => $confidenceLevel, 'size' => 'md'])
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 rounded-lg border border-zinc-200/90 bg-zinc-50/70 p-3 dark:border-zinc-800 dark:bg-zinc-900/50">
                        <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.confidence_methodology') }}</p>
                        <ul class="mt-1 space-y-1 text-sm text-zinc-700 dark:text-zinc-300">
                            <li>{{ __('admin.confidence_signal_definitions') }}: {{ ($confidenceSignals['short_definition'] || $confidenceSignals['full_definition']) ? __('admin.yes') : __('admin.no') }}</li>
                            <li>{{ __('admin.confidence_signal_translations') }}: {{ $publishedTranslationCount }}</li>
                            <li>{{ __('admin.confidence_signal_semantics') }}: {{ $confidenceSignals['semantic_relations'] ? __('admin.yes') : __('admin.no') }}</li>
                            <li>{{ __('admin.confidence_signal_examples') }}: {{ $confidenceSignals['examples'] ? __('admin.yes') : __('admin.no') }}</li>
                            <li>{{ __('admin.confidence_signal_validation') }}: {{ $confidenceSignals['validation'] ? __('admin.yes') : __('admin.no') }}</li>
                        </ul>
                    </div>

                    @if (($mediaTrustSignals['total'] ?? 0) > 0)
                        <div class="mt-4 rounded-lg border border-zinc-200/90 bg-zinc-50/70 p-3 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Media evidence quality</p>
                            <ul class="mt-1 space-y-1 text-sm text-zinc-700 dark:text-zinc-300">
                                <li>Technical media assets: {{ $mediaTrustSignals['total'] ?? 0 }}</li>
                                <li>Localized alt coverage: {{ $mediaTrustSignals['localized_alt'] ?? 0 }}/{{ $mediaTrustSignals['total'] ?? 0 }}</li>
                                <li>Localized captions: {{ $mediaTrustSignals['captioned'] ?? 0 }}/{{ $mediaTrustSignals['total'] ?? 0 }}</li>
                                <li>Process-stage annotated: {{ $mediaTrustSignals['process_staged'] ?? 0 }}/{{ $mediaTrustSignals['total'] ?? 0 }}</li>
                                <li>Source-documented assets: {{ $mediaTrustSignals['source_documented'] ?? 0 }}/{{ $mediaTrustSignals['total'] ?? 0 }}</li>
                                <li>Process/construction visuals: {{ $mediaTrustSignals['diagram_or_process'] ?? 0 }}/{{ $mediaTrustSignals['total'] ?? 0 }}</li>
                                @if ($mediaTrustSignals['priority_target'] ?? false)
                                    <li>Priority-concept high-value coverage: {{ $mediaTrustSignals['high_value_assets'] ?? 0 }} assets (target: >= 2)</li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    @if (filled($translation->industry_notes))
                        <div class="mt-4 rounded-lg border border-zinc-200/90 bg-zinc-50/70 p-3 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.industry_notes') }}</p>
                            <p class="mt-1 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">{{ $translation->industry_notes }}</p>
                        </div>
                    @endif

                    @if (filled($translation->editorial_notes))
                        <div class="mt-4 rounded-lg border border-zinc-200/90 bg-zinc-50/70 p-3 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.editorial_notes') }}</p>
                            <div class="mt-1 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">{!! nl2br(e($translation->editorial_notes)) !!}</div>
                        </div>
                    @endif

                    @if (filled($translation->source_reference_text))
                        <div class="mt-4 rounded-lg border border-zinc-200/90 bg-zinc-50/70 p-3 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.source_reference_text') }}</p>
                            <div class="mt-1 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">{!! nl2br(e($translation->source_reference_text)) !!}</div>
                        </div>
                    @endif

                    @if (filled($translation->meta_keywords) || filled($translation->seo_title) || filled($translation->seo_description))
                        <div class="mt-4 rounded-lg border border-zinc-200/90 bg-zinc-50/70 p-3 dark:border-zinc-800 dark:bg-zinc-900/50">
                            <p class="text-[11px] uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.seo_signals') }}</p>
                            <ul class="mt-1 space-y-1 text-sm text-zinc-700 dark:text-zinc-300">
                                @if (filled($translation->seo_title))
                                    <li><span class="font-semibold">{{ __('admin.seo_title') }}:</span> {{ $translation->seo_title }}</li>
                                @endif
                                @if (filled($translation->seo_description))
                                    <li><span class="font-semibold">{{ __('admin.meta_description') }}:</span> {{ \Illuminate\Support\Str::limit($translation->seo_description, 140) }}</li>
                                @endif
                                @if (filled($translation->meta_keywords))
                                    <li><span class="font-semibold">{{ __('admin.meta_keywords') }}:</span> {{ $translation->meta_keywords }}</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </section>
            </article>
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
