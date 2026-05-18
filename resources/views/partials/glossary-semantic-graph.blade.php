@props([
    'semanticGrouped' => [],
    'supersededBy' => null,
    'domainCoConcepts' => null,
    'workflowNeighbors' => null,
    'locale',
])

@php
    $g = is_array($semanticGrouped) ? $semanticGrouped : [];
    $domainCoConcepts = $domainCoConcepts ?? collect();
    $hasSemantic =
        ($g['synonyms'] ?? collect())->isNotEmpty()
        || ($g['broader'] ?? collect())->isNotEmpty()
        || ($g['narrower'] ?? collect())->isNotEmpty()
        || ($g['related'] ?? collect())->isNotEmpty()
        || ($g['industry_variants'] ?? collect())->isNotEmpty()
        || ($g['deprecated'] ?? collect())->isNotEmpty();
    $hasDomainCo = $domainCoConcepts->isNotEmpty();
    $w = is_array($workflowNeighbors) ? $workflowNeighbors : [];
    $hasWorkflowNav = ($w['upstream'] ?? collect())->isNotEmpty()
        || ($w['downstream'] ?? collect())->isNotEmpty()
        || ($w['route_alternatives'] ?? collect())->isNotEmpty();

    $sections = [
        ['semantic-synonyms', __('messages.semantic_synonyms'), $g['synonyms'] ?? collect(), true, __('messages.semantic_empty_synonyms')],
        ['semantic-broader', __('messages.semantic_broader'), $g['broader'] ?? collect(), true, __('messages.semantic_empty_broader')],
        ['semantic-narrower', __('messages.semantic_narrower'), $g['narrower'] ?? collect(), true, __('messages.semantic_empty_narrower')],
        ['semantic-related', __('messages.semantic_related'), $g['related'] ?? collect(), true, __('messages.semantic_empty_related')],
        ['semantic-industry-variants', __('messages.semantic_industry_variants'), $g['industry_variants'] ?? collect(), false, __('messages.semantic_empty_industry_variants')],
        ['semantic-deprecated', __('messages.semantic_deprecated'), $g['deprecated'] ?? collect(), false, __('messages.semantic_empty_deprecated')],
    ];
@endphp

@if ($supersededBy)
    <div
        class="rounded-lg border border-amber-200/90 bg-amber-50/90 px-3 py-3 text-[13px] leading-relaxed text-amber-950 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-100"
        role="note"
    >
        <p class="font-semibold">{{ __('messages.semantic_entry_status') }}</p>
        <p class="mt-1 text-[12px] text-amber-900/90 dark:text-amber-200/90">
            {{ __('messages.semantic_deprecated_notice') }}
        </p>
        <p class="mt-2">
            <a
                href="{{ $supersededBy->glossaryUrl() }}"
                wire:navigate
                class="font-medium text-amber-950 underline decoration-amber-400 underline-offset-2 hover:decoration-amber-700 dark:text-amber-50 dark:decoration-amber-600"
            >
                {{ __('messages.semantic_superseded_by', ['term' => $supersededBy->term]) }}
            </a>
        </p>
    </div>
@endif

@if ($hasSemantic || $hasDomainCo || $hasWorkflowNav)
    <nav
        class="rounded-lg border border-zinc-200/90 bg-zinc-50/80 px-3 py-3 dark:border-zinc-800 dark:bg-zinc-900/40"
        aria-label="{{ __('messages.semantic_explore') }}"
    >
        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-zinc-600 dark:text-zinc-400">
            {{ __('messages.semantic_explore') }}
        </p>
        <p class="mt-1 text-[12px] leading-relaxed text-zinc-600 dark:text-zinc-400">
            {{ __('messages.semantic_jump_help') }}
        </p>
        <div class="mt-2">
            @include('partials.ui.locale-indicator', ['code' => $locale, 'active' => true])
        </div>
        <ul class="mt-2 flex flex-wrap gap-x-3 gap-y-1.5 text-[12px] font-medium text-zinc-800 dark:text-zinc-200">
            @if ($hasWorkflowNav)
                <li>
                    <a href="#semantic-workflow" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('messages.semantic_workflow_context') }}</a>
                </li>
            @endif
            @if (($g['synonyms'] ?? collect())->isNotEmpty())
                <li><a href="#semantic-synonyms" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('messages.semantic_synonyms') }}</a></li>
            @endif
            @if (($g['broader'] ?? collect())->isNotEmpty())
                <li><a href="#semantic-broader" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('messages.semantic_broader') }}</a></li>
            @endif
            @if (($g['narrower'] ?? collect())->isNotEmpty())
                <li><a href="#semantic-narrower" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('messages.semantic_narrower') }}</a></li>
            @endif
            @if (($g['related'] ?? collect())->isNotEmpty())
                <li><a href="#semantic-related" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('messages.semantic_related') }}</a></li>
            @endif
            @if (($g['industry_variants'] ?? collect())->isNotEmpty())
                <li>
                    <a href="#semantic-industry-variants" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('messages.semantic_industry_variants') }}</a>
                </li>
            @endif
            @if (($g['deprecated'] ?? collect())->isNotEmpty())
                <li>
                    <a href="#semantic-deprecated" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('messages.semantic_deprecated') }}</a>
                </li>
            @endif
            @if ($hasDomainCo)
                <li>
                    <a href="#semantic-domain-context" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('messages.semantic_frequently_associated') }}</a>
                </li>
            @endif
        </ul>

        <div class="mt-3 flex flex-wrap gap-1.5">
            @include('partials.ui.semantic-chip', ['label' => __('messages.semantic_synonyms').': '.(($g['synonyms'] ?? collect())->count()), 'interactive' => false])
            @include('partials.ui.semantic-chip', ['label' => __('messages.semantic_broader').': '.(($g['broader'] ?? collect())->count()), 'interactive' => false])
            @include('partials.ui.semantic-chip', ['label' => __('messages.semantic_narrower').': '.(($g['narrower'] ?? collect())->count()), 'interactive' => false])
            @include('partials.ui.semantic-chip', ['label' => __('messages.semantic_related').': '.(($g['related'] ?? collect())->count()), 'interactive' => false])
        </div>
    </nav>
@endif

@foreach ($sections as [$sectionId, $sectionTitle, $items, $alwaysRender, $emptyLabel])
    @if ($alwaysRender || $items->isNotEmpty())
        <section id="{{ $sectionId }}" class="space-y-2 border-t border-zinc-200/90 pt-6 dark:border-zinc-800" aria-labelledby="{{ $sectionId }}-heading">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <h3 id="{{ $sectionId }}-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                    {{ $sectionTitle }}
                </h3>
                <div class="flex items-center gap-2">
                    <p class="text-[10px] font-medium uppercase tracking-wide text-zinc-400 dark:text-zinc-500">
                        {{ __('messages.semantic_concept_graph') }}
                    </p>
                    <span class="rounded border border-zinc-200/80 bg-zinc-50 px-1.5 py-0.5 text-[10px] font-semibold text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400">
                        {{ $items->count() }}
                    </span>
                </div>
            </div>
            @if ($items->isNotEmpty())
                <ul class="divide-y divide-zinc-200/80 rounded-lg border border-zinc-200/90 dark:divide-zinc-800 dark:border-zinc-800" role="list">
                    @foreach ($items as $peer)
                        <li class="flex flex-col gap-0.5 px-3 py-2.5 sm:flex-row sm:items-baseline sm:justify-between sm:gap-3">
                            <a
                                href="{{ $peer->glossaryUrl() }}"
                                wire:navigate
                                class="text-[14px] font-medium text-zinc-900 underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:text-zinc-100 dark:decoration-zinc-600 dark:hover:decoration-zinc-400"
                            >
                                {{ $peer->term }}
                            </a>
                            @if (filled($peer->short_definition))
                                <p class="max-w-xl text-[12px] leading-snug text-zinc-500 dark:text-zinc-400">
                                    {{ \Illuminate\Support\Str::limit($peer->short_definition, 140) }}
                                </p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                @include('partials.ui.empty-state', ['message' => $emptyLabel, 'compact' => true])
            @endif
        </section>
    @endif
@endforeach

@if ($hasDomainCo)
    <section id="semantic-domain-context" class="space-y-2 border-t border-zinc-200/90 pt-6 dark:border-zinc-800" aria-labelledby="semantic-domain-context-heading">
        <h3 id="semantic-domain-context-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
            {{ __('messages.semantic_frequently_associated') }}
        </h3>
        <p class="text-[12px] leading-relaxed text-zinc-600 dark:text-zinc-400">
            {{ __('messages.semantic_frequently_associated_help') }}
        </p>
        <ul class="mt-2 divide-y divide-zinc-200/80 rounded-lg border border-zinc-200/90 dark:divide-zinc-800 dark:border-zinc-800" role="list">
            @foreach ($domainCoConcepts as $peer)
                <li class="flex flex-col gap-0.5 px-3 py-2.5 sm:flex-row sm:items-baseline sm:justify-between sm:gap-3">
                    <a
                        href="{{ $peer->glossaryUrl() }}"
                        wire:navigate
                        class="text-[14px] font-medium text-zinc-900 underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:text-zinc-100 dark:decoration-zinc-600 dark:hover:decoration-zinc-400"
                    >
                        {{ $peer->term }}
                    </a>
                    @if (filled($peer->short_definition))
                        <p class="max-w-xl text-[12px] leading-snug text-zinc-500 dark:text-zinc-400">
                            {{ \Illuminate\Support\Str::limit($peer->short_definition, 120) }}
                        </p>
                    @endif
                </li>
            @endforeach
        </ul>
    </section>
@endif

@if (! $hasSemantic && ! $hasDomainCo)
    <section class="space-y-2 border-t border-zinc-200/90 pt-6 dark:border-zinc-800" aria-labelledby="semantic-empty-heading">
        <h3 id="semantic-empty-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
            {{ __('messages.semantic_explore') }}
        </h3>
        @include('partials.ui.empty-state', ['message' => __('messages.semantic_empty_all'), 'compact' => true])
    </section>
@endif
