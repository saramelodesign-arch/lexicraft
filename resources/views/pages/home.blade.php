<x-layouts::site
    :title="$pageTitle"
    :meta-description="$metaDescription"
    :canonical="$canonical"
    :og-url="$canonical"
    :json-ld-blocks="$jsonLdBlocks"
>
    @push('meta')
        @include('partials.hreflang-alternates', ['alternates' => $alternates, 'xDefaultUrl' => $xDefaultUrl])
    @endpush

    <livewire:layout.header />

    <main id="content" class="flex-1">
        <header class="border-b border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950" aria-labelledby="hero-heading">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)] lg:gap-8">
                    <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 dark:border-zinc-800 dark:bg-zinc-900/50">
                        <p class="text-[11px] font-medium uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-500">{{ __('ui.home_platform_kicker') }}</p>
                        <h1
                            id="hero-heading"
                            class="mt-3 max-w-2xl text-pretty text-3xl font-semibold leading-tight tracking-tight text-zinc-900 dark:text-zinc-50"
                        >
                            {{ __('ui.home_hero_title') }}
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                            {{ __('ui.home_hero_description') }}
                        </p>

                        <div class="mt-5 max-w-2xl">
                            <livewire:search.global-search />
                        </div>

                        <div class="mt-4 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-zinc-700 dark:text-zinc-300">
                            <span class="font-semibold">12,400</span>
                            <span class="text-zinc-400">&bull;</span>
                            <span>{{ __('ui.home_terms_label') }}</span>
                            <span class="text-zinc-400">&bull;</span>
                            <span>6 {{ __('ui.languages_count_label') }}</span>
                            <span class="text-zinc-400">&bull;</span>
                            <span>13 {{ __('ui.domains_count_label') }}</span>
                        </div>

                        <div class="mt-4 border-t border-zinc-200 pt-4 dark:border-zinc-800">
                            <div class="flex flex-wrap gap-x-2 gap-y-1 text-sm text-zinc-500 dark:text-zinc-400">
                                @foreach ([__('ui.home_topics_footwear'), __('ui.home_topics_leather_goods'), __('ui.home_topics_cad_cam'), __('ui.home_topics_manufacturing'), __('ui.home_topics_quality_control')] as $topic)
                                    <span>{{ $topic }}</span>
                                    @if (! $loop->last)
                                        <span>&bull;</span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <aside id="about-lexicraft" class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 dark:border-zinc-800 dark:bg-zinc-900/50">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 inline-flex size-6 items-center justify-center rounded-md border border-zinc-200 bg-white text-zinc-600 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300">
                                <svg class="size-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="M4 4.5A1.5 1.5 0 0 1 5.5 3H16v14H5.5A1.5 1.5 0 0 0 4 18V4.5Z" />
                                    <path d="M8 6h5M8 9h5" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                                    {{ __('ui.about_lexicraft_glossary') }}
                                </h2>
                                <p class="mt-2 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                                    {{ __('seo.footer_platform_description') }}
                                </p>
                            </div>
                        </div>

                        <ul class="mt-6 space-y-4">
                            @foreach ([
                                ['title' => __('ui.multilingual'), 'text' => __('ui.multilingual_feature_description')],
                                ['title' => __('ui.technical_verified'), 'text' => __('ui.technical_verified_feature_description')],
                                ['title' => __('ui.structured_by_domain'), 'text' => __('ui.structured_by_domain_feature_description')],
                                ['title' => __('ui.built_for_professionals'), 'text' => __('ui.built_for_professionals_feature_description')],
                            ] as $feature)
                                <li class="flex gap-3">
                                    <span class="mt-1 inline-block size-2 rounded-full bg-zinc-300 dark:bg-zinc-600"></span>
                                    <div>
                                        <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200">{{ $feature['title'] }}</p>
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $feature['text'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ route('domains.index', ['locale' => $locale], absolute: false) }}" wire:navigate class="mt-5 inline-flex items-center text-sm font-medium text-zinc-800 hover:text-zinc-950 dark:text-zinc-200 dark:hover:text-zinc-50">
                            {{ __('ui.learn_more_about_glossary') }} <span class="ms-1">&rarr;</span>
                        </a>
                    </aside>
                </div>

                <section class="mt-8 rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-800 dark:bg-zinc-900/40" aria-labelledby="alphabet-heading">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 id="alphabet-heading" class="text-sm font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                                {{ __('ui.browse_by_letter') }}
                            </h2>
                            <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400">{{ __('ui.browse_by_letter_description') }}</p>
                        </div>
                        <nav class="flex flex-wrap gap-1" aria-label="{{ __('ui.glossary_az') }}">
                            @foreach (range('A', 'Z') as $letter)
                                <a
                                    wire:navigate
                                    href="{{ route('glossary.letter', ['locale' => $locale, 'letter' => strtolower($letter)]) }}"
                                    class="inline-flex min-w-[1.8rem] justify-center rounded-md border border-zinc-200 bg-white px-1.5 py-1 text-center text-[12px] font-medium tabular-nums text-zinc-700 hover:border-zinc-400 hover:text-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-300 dark:hover:border-zinc-500 dark:hover:text-zinc-50"
                                >
                                    {{ $letter }}
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </section>
            </div>
        </header>

        <section class="scroll-mt-20 border-b border-zinc-200 bg-white py-10 dark:border-zinc-800 dark:bg-zinc-950 sm:py-12" aria-labelledby="domains-heading">
            <livewire:domains.domain-grid />
        </section>

        <section
            class="scroll-mt-20 border-b border-zinc-200 bg-zinc-50 py-9 dark:border-zinc-800 dark:bg-zinc-950/80"
            aria-labelledby="learning-section-heading"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 id="learning-section-heading" class="text-sm font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                    {{ __('ui.learning') }}
                </h2>
                <p class="mt-1.5 max-w-xl text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ __('ui.home_learning_description') }}
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a
                        href="{{ route('learning.index', ['locale' => $locale]) }}"
                        wire:navigate
                        class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-3 py-2 text-xs font-medium text-zinc-800 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                    >
                        {{ __('ui.learning_hub') }}
                    </a>
                    <a
                        href="{{ route('learning.flashcards', ['locale' => $locale]) }}"
                        wire:navigate
                        class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-3 py-2 text-xs font-medium text-zinc-800 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                    >
                        {{ __('ui.flashcards') }}
                    </a>
                    <a
                        href="{{ route('learning.semantic', ['locale' => $locale]) }}"
                        wire:navigate
                        class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-3 py-2 text-xs font-medium text-zinc-800 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                    >
                        {{ __('ui.semantic_practice') }}
                    </a>
                    <a
                        href="{{ route('learning.quizzes', ['locale' => $locale]) }}"
                        wire:navigate
                        class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-3 py-2 text-xs font-medium text-zinc-800 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                    >
                        {{ __('ui.quizzes') }}
                    </a>
                </div>
            </div>
        </section>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
