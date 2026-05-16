@php
    use App\Support\Locales;

    $firstLetter = mb_strtoupper(mb_substr($translation->term, 0, 1, 'UTF-8'), 'UTF-8');
    $letterRoute = route('glossary.letter', ['locale' => $locale, 'letter' => strtolower($firstLetter)], absolute: false);
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
        <div class="mx-auto max-w-3xl space-y-8">
            <div class="space-y-3">
                <flux:link :href="route('home', ['locale' => $locale])" wire:navigate variant="subtle" class="text-[13px] text-zinc-600 dark:text-zinc-400">
                    ← {{ __('ui.home_back') }}
                </flux:link>
                @isset($breadcrumbs)
                    @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                @endisset
                <flux:link :href="$letterRoute" wire:navigate variant="subtle" class="block text-[13px] text-zinc-600 dark:text-zinc-400">
                    ← {{ __('search.glossary_letter_with_letter', ['letter' => $firstLetter]) }}
                </flux:link>
            </div>

            <article class="space-y-6">
                <header class="space-y-2 border-b border-zinc-200/90 pb-6 dark:border-zinc-800">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                        {{ __('search.concept') }}
                    </p>
                    <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                        {{ $translation->term }}
                    </h1>
                    @if ($concept->domains->isNotEmpty())
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            @foreach ($concept->domains as $domain)
                                @php
                                    $dt = $domain->translations->firstWhere('language_id', $languageId);
                                    $dlabel = $dt?->name ?? $domain->slug;
                                @endphp
                                @if ($dt !== null && filled($dt->slug))
                                    <flux:badge size="sm" variant="outline">
                                        <a
                                            href="{{ route('domains.show', ['locale' => $locale, 'slug' => $dt->slug]) }}"
                                            wire:navigate
                                            class="hover:underline"
                                        >
                                            {{ $dlabel }}
                                        </a>
                                    </flux:badge>
                                @else
                                    <flux:badge size="sm" variant="outline">{{ $dlabel }}</flux:badge>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </header>

                @include('partials.glossary-concept-media', [
                    'concept' => $concept,
                    'translation' => $translation,
                    'locale' => $locale,
                ])

                @if (filled($translation->short_definition))
                    <section aria-labelledby="short-def-heading" class="space-y-2">
                        <h2 id="short-def-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                            {{ __('search.summary') }}
                        </h2>
                        <p class="text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">
                            {{ $translation->short_definition }}
                        </p>
                    </section>
                @endif

                @if (filled($translation->full_definition))
                    <section aria-labelledby="full-def-heading" class="space-y-2">
                        <h2 id="full-def-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                            {{ __('search.definition') }}
                        </h2>
                        <div class="prose prose-sm max-w-none text-zinc-700 dark:prose-invert dark:text-zinc-300">
                            {!! nl2br(e($translation->full_definition)) !!}
                        </div>
                    </section>
                @endif

                @if (filled($translation->industry_notes))
                    <section aria-labelledby="notes-heading" class="space-y-2">
                        <h2 id="notes-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                            {{ __('search.industry_notes') }}
                        </h2>
                        <p class="text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">
                            {{ $translation->industry_notes }}
                        </p>
                    </section>
                @endif

                @if ($translation->examples->isNotEmpty())
                    <section aria-labelledby="examples-heading" class="space-y-3">
                        <h2 id="examples-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                            {{ __('admin.examples') }}
                        </h2>
                        <ul class="space-y-3" role="list">
                            @foreach ($translation->examples as $ex)
                                <li class="rounded-lg border border-zinc-200/90 bg-white p-3 text-sm dark:border-zinc-800 dark:bg-zinc-900/40">
                                    <p class="leading-relaxed text-zinc-800 dark:text-zinc-200">{{ $ex->example }}</p>
                                    @if (filled($ex->context))
                                        <p class="mt-1 text-[11px] font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                                            {{ $ex->context }}
                                        </p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @include('partials.glossary-semantic-graph', [
                    'semanticGrouped' => $semanticGrouped,
                    'supersededBy' => $supersededBy,
                    'domainCoConcepts' => $domainCoConcepts,
                    'locale' => $locale,
                ])

                <nav class="border-t border-zinc-200/90 pt-6 dark:border-zinc-800" aria-label="{{ __('search.other_languages') }}">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                        {{ __('search.this_concept_other_languages') }}
                    </p>
                    <ul class="mt-2 flex flex-wrap gap-2">
                        @foreach (Locales::codes() as $code)
                            @if (isset($alternates[$code]))
                                <li>
                                    <a
                                        href="{{ $alternates[$code] }}"
                                        @class([
                                            'rounded-md border px-2 py-1 text-[12px] font-medium',
                                            'border-zinc-900 bg-zinc-900 text-white dark:border-zinc-100 dark:bg-zinc-100 dark:text-zinc-900' => $code === $locale,
                                            'border-zinc-200 text-zinc-700 hover:border-zinc-300 dark:border-zinc-700 dark:text-zinc-200 dark:hover:border-zinc-500' => $code !== $locale,
                                        ])
                                        @if ($code === $locale) aria-current="page" @endif
                                    >
                                        {{ strtoupper($code) }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </nav>
            </article>
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
