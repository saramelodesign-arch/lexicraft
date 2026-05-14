@props([
    'semanticGrouped' => [],
    'supersededBy' => null,
    'domainCoConcepts' => null,
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

    $sections = [
        ['semantic-synonyms', __('Synonyms'), $g['synonyms'] ?? collect()],
        ['semantic-broader', __('Broader concepts'), $g['broader'] ?? collect()],
        ['semantic-narrower', __('Narrower concepts'), $g['narrower'] ?? collect()],
        ['semantic-related', __('Related terminology'), $g['related'] ?? collect()],
        ['semantic-industry-variants', __('Industry variants'), $g['industry_variants'] ?? collect()],
        ['semantic-deprecated', __('Deprecated terminology'), $g['deprecated'] ?? collect()],
    ];
@endphp

@if ($supersededBy)
    <div
        class="rounded-lg border border-amber-200/90 bg-amber-50/90 px-3 py-3 text-[13px] leading-relaxed text-amber-950 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-100"
        role="note"
    >
        <p class="font-semibold">{{ __('Entry status') }}</p>
        <p class="mt-1 text-[12px] text-amber-900/90 dark:text-amber-200/90">
            {{ __('This concept is marked as deprecated in favor of another published entry.') }}
        </p>
        <p class="mt-2">
            <a
                href="{{ $supersededBy->glossaryUrl() }}"
                wire:navigate
                class="font-medium text-amber-950 underline decoration-amber-400 underline-offset-2 hover:decoration-amber-700 dark:text-amber-50 dark:decoration-amber-600"
            >
                {{ __('Superseded by: :term', ['term' => $supersededBy->term]) }}
            </a>
        </p>
    </div>
@endif

@if ($hasSemantic || $hasDomainCo)
    <nav
        class="rounded-lg border border-zinc-200/90 bg-zinc-50/80 px-3 py-3 dark:border-zinc-800 dark:bg-zinc-900/40"
        aria-label="{{ __('Explore related terminology') }}"
    >
        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-zinc-600 dark:text-zinc-400">
            {{ __('Explore related terminology') }}
        </p>
        <p class="mt-1 text-[12px] leading-relaxed text-zinc-600 dark:text-zinc-400">
            {{ __('Jump to semantic sections. Links stay within the same locale.') }}
        </p>
        <ul class="mt-2 flex flex-wrap gap-x-3 gap-y-1.5 text-[12px] font-medium text-zinc-800 dark:text-zinc-200">
            @if (($g['synonyms'] ?? collect())->isNotEmpty())
                <li><a href="#semantic-synonyms" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('Synonyms') }}</a></li>
            @endif
            @if (($g['broader'] ?? collect())->isNotEmpty())
                <li><a href="#semantic-broader" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('Broader concepts') }}</a></li>
            @endif
            @if (($g['narrower'] ?? collect())->isNotEmpty())
                <li><a href="#semantic-narrower" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('Narrower concepts') }}</a></li>
            @endif
            @if (($g['related'] ?? collect())->isNotEmpty())
                <li><a href="#semantic-related" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('Related terminology') }}</a></li>
            @endif
            @if (($g['industry_variants'] ?? collect())->isNotEmpty())
                <li>
                    <a href="#semantic-industry-variants" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('Industry variants') }}</a>
                </li>
            @endif
            @if (($g['deprecated'] ?? collect())->isNotEmpty())
                <li>
                    <a href="#semantic-deprecated" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('Deprecated terminology') }}</a>
                </li>
            @endif
            @if ($hasDomainCo)
                <li>
                    <a href="#semantic-domain-context" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 dark:decoration-zinc-600">{{ __('Frequently associated concepts') }}</a>
                </li>
            @endif
        </ul>
    </nav>
@endif

@foreach ($sections as [$sectionId, $sectionTitle, $items])
    @if ($items->isNotEmpty())
        <section id="{{ $sectionId }}" class="space-y-2 border-t border-zinc-200/90 pt-6 dark:border-zinc-800" aria-labelledby="{{ $sectionId }}-heading">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <h2 id="{{ $sectionId }}-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                    {{ $sectionTitle }}
                </h2>
                <p class="text-[10px] font-medium uppercase tracking-wide text-zinc-400 dark:text-zinc-500">
                    {{ __('Concept graph') }}
                </p>
            </div>
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
        </section>
    @endif
@endforeach

@if ($hasDomainCo)
    <section id="semantic-domain-context" class="space-y-2 border-t border-zinc-200/90 pt-6 dark:border-zinc-800" aria-labelledby="semantic-domain-context-heading">
        <h2 id="semantic-domain-context-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
            {{ __('Frequently associated concepts') }}
        </h2>
        <p class="text-[12px] leading-relaxed text-zinc-600 dark:text-zinc-400">
            {{ __('Other published terms that share industrial domains with this concept—useful for line-wide discovery without leaving the glossary.') }}
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
