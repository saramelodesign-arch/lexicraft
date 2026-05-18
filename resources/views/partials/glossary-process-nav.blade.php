@props([
    'workflowNeighbors' => [],
    'workflowSignals' => [],
    'readingJourneys' => collect(),
    'locale',
])

@php
    $w = is_array($workflowNeighbors) ? $workflowNeighbors : [];
    $upstream = $w['upstream'] ?? collect();
    $downstream = $w['downstream'] ?? collect();
    $routes = $w['route_alternatives'] ?? collect();
    $signals = is_array($workflowSignals) ? $workflowSignals : [];
    $signalFollows = ($signals['follows'] ?? collect())->take(3);
    $signalPrecedes = ($signals['precedes'] ?? collect())->take(3);
    $signalAlternatives = ($signals['alternatives'] ?? collect())->take(3);
    $signalUsedIn = ($signals['used_in_routes'] ?? collect())->take(3);
    $signalPairs = ($signals['often_paired_with'] ?? collect())->take(3);
    $signalStages = collect($signals['related_stages'] ?? [])->filter(fn ($stage) => is_string($stage) && $stage !== '')->take(3);
    $journeys = $readingJourneys instanceof \Illuminate\Support\Collection ? $readingJourneys : collect($readingJourneys);
    $hasJourneys = $journeys->isNotEmpty();
    $hasSignals = $signalFollows->isNotEmpty() || $signalPrecedes->isNotEmpty() || $signalAlternatives->isNotEmpty() || $signalUsedIn->isNotEmpty() || $signalPairs->isNotEmpty() || $signalStages->isNotEmpty();
    $hasWorkflow = $upstream->isNotEmpty() || $downstream->isNotEmpty() || $routes->isNotEmpty();
@endphp

@if ($hasWorkflow)
    <section
        id="semantic-workflow"
        class="mb-6 space-y-4 rounded-lg border border-sky-200/80 bg-sky-50/60 px-4 py-4 dark:border-sky-900/50 dark:bg-sky-950/25"
        aria-labelledby="semantic-workflow-heading"
    >
        <div>
            <h3 id="semantic-workflow-heading" class="text-[13px] font-semibold text-sky-950 dark:text-sky-100">
                {{ __('messages.semantic_workflow_context') }}
            </h3>
            <p class="mt-1 text-[12px] leading-relaxed text-sky-900/85 dark:text-sky-200/85">
                {{ __('messages.semantic_workflow_context_help') }}
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            @if ($upstream->isNotEmpty())
                <div class="space-y-2">
                    <h4 class="text-[11px] font-semibold uppercase tracking-[0.12em] text-sky-800 dark:text-sky-300">
                        {{ __('messages.semantic_workflow_upstream') }}
                    </h4>
                    <p class="text-[11px] leading-snug text-sky-900/75 dark:text-sky-300/90">
                        {{ __('messages.semantic_workflow_upstream_help') }}
                    </p>
                    <ul class="divide-y divide-sky-200/70 rounded-md border border-sky-200/80 bg-white/70 dark:divide-sky-900/60 dark:border-sky-900/50 dark:bg-zinc-950/40" role="list">
                        @foreach ($upstream as $peer)
                            <li class="px-3 py-2">
                                <a
                                    href="{{ $peer->glossaryUrl() }}"
                                    wire:navigate
                                    class="text-[13px] font-medium text-sky-950 underline decoration-sky-300 underline-offset-2 hover:decoration-sky-600 dark:text-sky-50 dark:decoration-sky-700"
                                >
                                    {{ $peer->term }}
                                </a>
                                @if (filled($peer->short_definition))
                                    <p class="mt-0.5 text-[11px] leading-snug text-zinc-600 dark:text-zinc-400">
                                        {{ \Illuminate\Support\Str::limit($peer->short_definition, 100) }}
                                    </p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($downstream->isNotEmpty())
                <div class="space-y-2">
                    <h4 class="text-[11px] font-semibold uppercase tracking-[0.12em] text-sky-800 dark:text-sky-300">
                        {{ __('messages.semantic_workflow_downstream') }}
                    </h4>
                    <p class="text-[11px] leading-snug text-sky-900/75 dark:text-sky-300/90">
                        {{ __('messages.semantic_workflow_downstream_help') }}
                    </p>
                    <ul class="divide-y divide-sky-200/70 rounded-md border border-sky-200/80 bg-white/70 dark:divide-sky-900/60 dark:border-sky-900/50 dark:bg-zinc-950/40" role="list">
                        @foreach ($downstream as $peer)
                            <li class="px-3 py-2">
                                <a
                                    href="{{ $peer->glossaryUrl() }}"
                                    wire:navigate
                                    class="text-[13px] font-medium text-sky-950 underline decoration-sky-300 underline-offset-2 hover:decoration-sky-600 dark:text-sky-50 dark:decoration-sky-700"
                                >
                                    {{ $peer->term }}
                                </a>
                                @if (filled($peer->short_definition))
                                    <p class="mt-0.5 text-[11px] leading-snug text-zinc-600 dark:text-zinc-400">
                                        {{ \Illuminate\Support\Str::limit($peer->short_definition, 100) }}
                                    </p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        @if ($hasJourneys)
            <div class="space-y-2 border-t border-sky-200/70 pt-4 dark:border-sky-900/50">
                <h4 class="text-[11px] font-semibold uppercase tracking-[0.12em] text-sky-800 dark:text-sky-300">
                    {{ __('messages.semantic_reading_journeys') }}
                </h4>
                <p class="text-[11px] leading-snug text-sky-900/75 dark:text-sky-300/90">
                    {{ __('messages.semantic_reading_journeys_help') }}
                </p>
                <div class="space-y-2">
                    @foreach ($journeys as $journey)
                        @php
                            $journeyItems = $journey['items'] ?? collect();
                            $journeyKey = $journey['key'] ?? null;
                        @endphp
                        @if ($journeyItems instanceof \Illuminate\Support\Collection && $journeyItems->isNotEmpty() && is_string($journeyKey))
                            <div class="rounded-md border border-sky-200/70 bg-white/70 px-3 py-2 dark:border-sky-900/50 dark:bg-zinc-950/40">
                                <p class="text-[12px] font-semibold text-sky-950 dark:text-sky-100">
                                    {{ __('messages.'.$journeyKey) }}
                                </p>
                                <ul class="mt-1 flex flex-wrap items-center gap-1.5 text-[12px]" role="list">
                                    @foreach ($journeyItems as $peer)
                                        <li class="inline-flex items-center gap-1.5">
                                            <a
                                                href="{{ $peer->glossaryUrl() }}"
                                                wire:navigate
                                                class="underline decoration-sky-300 underline-offset-2 hover:decoration-sky-600 dark:decoration-sky-700"
                                            >
                                                {{ $peer->term }}
                                            </a>
                                            @if (! $loop->last)
                                                <span class="text-sky-500/70 dark:text-sky-400/70">→</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        @if ($hasSignals)
            <div class="space-y-2 border-t border-sky-200/70 pt-4 dark:border-sky-900/50">
                <h4 class="text-[11px] font-semibold uppercase tracking-[0.12em] text-sky-800 dark:text-sky-300">
                    {{ __('messages.semantic_workflow_signals') }}
                </h4>
                <p class="text-[11px] leading-snug text-sky-900/75 dark:text-sky-300/90">
                    {{ __('messages.semantic_workflow_signals_help') }}
                </p>
                <div class="space-y-1.5">
                    @if ($signalStages->isNotEmpty())
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($signalStages as $stage)
                                @include('partials.ui.semantic-chip', [
                                    'label' => __('messages.semantic_signal_related_stage').': '.$stage,
                                    'interactive' => false,
                                ])
                            @endforeach
                        </div>
                    @endif
                    <div class="flex flex-wrap gap-1.5">
                        @if ($signalFollows->isNotEmpty())
                            @foreach ($signalFollows as $peer)
                                @include('partials.ui.semantic-chip', [
                                    'label' => __('messages.semantic_signal_follows').': '.$peer->term,
                                    'href' => $peer->glossaryUrl(),
                                ])
                            @endforeach
                        @endif
                        @if ($signalPrecedes->isNotEmpty())
                            @foreach ($signalPrecedes as $peer)
                                @include('partials.ui.semantic-chip', [
                                    'label' => __('messages.semantic_signal_precedes').': '.$peer->term,
                                    'href' => $peer->glossaryUrl(),
                                ])
                            @endforeach
                        @endif
                        @if ($signalUsedIn->isNotEmpty())
                            @foreach ($signalUsedIn as $peer)
                                @include('partials.ui.semantic-chip', [
                                    'label' => __('messages.semantic_signal_used_in').': '.$peer->term,
                                    'href' => $peer->glossaryUrl(),
                                ])
                            @endforeach
                        @endif
                        @if ($signalAlternatives->isNotEmpty())
                            @foreach ($signalAlternatives as $peer)
                                @include('partials.ui.semantic-chip', [
                                    'label' => __('messages.semantic_signal_alternative_route').': '.$peer->term,
                                    'href' => $peer->glossaryUrl(),
                                ])
                            @endforeach
                        @endif
                        @if ($signalPairs->isNotEmpty())
                            @foreach ($signalPairs as $peer)
                                @include('partials.ui.semantic-chip', [
                                    'label' => __('messages.semantic_signal_often_paired_with').': '.$peer->term,
                                    'href' => $peer->glossaryUrl(),
                                ])
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if ($routes->isNotEmpty())
            <div class="space-y-2 border-t border-sky-200/70 pt-4 dark:border-sky-900/50">
                <h4 class="text-[11px] font-semibold uppercase tracking-[0.12em] text-sky-800 dark:text-sky-300">
                    {{ __('messages.semantic_workflow_route_alternatives') }}
                </h4>
                <p class="text-[11px] leading-snug text-sky-900/75 dark:text-sky-300/90">
                    {{ __('messages.semantic_workflow_route_help') }}
                </p>
                <ul class="flex flex-wrap gap-2" role="list">
                    @foreach ($routes as $peer)
                        <li>
                            @include('partials.ui.semantic-chip', [
                                'label' => $peer->term,
                                'href' => $peer->glossaryUrl(),
                            ])
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </section>
@endif
