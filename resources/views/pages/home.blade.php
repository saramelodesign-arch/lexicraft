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
        <header
            class="border-b border-zinc-200 bg-white dark:border-zinc-800/80 dark:bg-zinc-950"
            aria-labelledby="hero-heading"
        >
            <div class="mx-auto max-w-7xl px-4 pb-10 pt-8 sm:px-6 sm:pb-12 sm:pt-10 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <p class="text-[11px] font-medium uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-500">
                        {{ __('Multilingual terminology platform') }}
                    </p>
                    <h1
                        id="hero-heading"
                        class="mt-3 text-balance text-2xl font-semibold leading-snug tracking-tight text-zinc-900 sm:text-3xl sm:leading-tight dark:text-zinc-50"
                    >
                        {{ __('Industrial terminology for footwear and leather goods.') }}
                    </h1>
                    <p class="mx-auto mt-3 max-w-lg text-pretty text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                        {{ __('Search by term, translation, definition, synonym, or domain—built for technical teams and suppliers.') }}
                    </p>
                </div>

                <div class="mx-auto mt-8 max-w-xl sm:mt-9">
                    <livewire:search.global-search />
                </div>

                <div class="mx-auto mt-5 flex max-w-xl flex-wrap justify-center gap-1.5 sm:mt-6" role="list" aria-label="{{ __('Example queries') }}">
                    @foreach ([__('Last'), __('Skiving'), __('Pattern allowance'), __('CAD nesting'), __('Defect codes')] as $pill)
                        <span
                            role="listitem"
                            class="inline-flex cursor-default items-center rounded-md border border-zinc-200/90 bg-zinc-50 px-2.5 py-1 text-[11px] font-medium text-zinc-600 transition-colors hover:border-zinc-300 hover:bg-white hover:text-zinc-800 dark:border-zinc-700/90 dark:bg-zinc-900/40 dark:text-zinc-400 dark:hover:border-zinc-600 dark:hover:bg-zinc-900 dark:hover:text-zinc-200"
                        >
                            {{ $pill }}
                        </span>
                    @endforeach
                </div>
            </div>
        </header>

        <section
            class="border-b border-zinc-200 bg-zinc-50 py-8 dark:border-zinc-800 dark:bg-zinc-950"
            aria-labelledby="alphabet-heading"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-xl border border-zinc-200/90 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900/40 dark:shadow-none sm:p-5">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between sm:gap-4">
                        <div>
                            <h2 id="alphabet-heading" class="text-sm font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                                {{ __('Browse by letter') }}
                            </h2>
                            <p class="mt-0.5 text-xs leading-relaxed text-zinc-500 dark:text-zinc-400">
                                {{ __('A–Z index for the current locale.') }}
                            </p>
                        </div>
                    </div>
                    <nav class="mt-4 flex flex-wrap gap-1" aria-label="{{ __('Glossary A–Z') }}">
                        @foreach (range('A', 'Z') as $letter)
                            <a
                                wire:navigate
                                href="{{ route('glossary.letter', ['locale' => $locale, 'letter' => strtolower($letter)]) }}"
                                class="inline-flex min-w-[1.75rem] justify-center rounded-md border border-zinc-200/90 bg-zinc-50 px-1.5 py-1 text-center text-[12px] font-medium tabular-nums text-zinc-700 transition-colors hover:border-zinc-400 hover:bg-white hover:text-zinc-900 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-300 dark:hover:border-zinc-500 dark:hover:bg-zinc-800 dark:hover:text-zinc-50"
                            >
                                {{ $letter }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>
        </section>

        <section class="scroll-mt-20 border-b border-zinc-200 bg-white py-10 dark:border-zinc-800 dark:bg-zinc-950 sm:py-12" aria-labelledby="domains-heading">
            <livewire:domains.domain-grid />
        </section>

        <section
            class="scroll-mt-20 border-b border-zinc-200 bg-zinc-50 py-9 dark:border-zinc-800 dark:bg-zinc-950/80"
            aria-labelledby="learning-section-heading"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 id="learning-section-heading" class="text-sm font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                    {{ __('Learning') }}
                </h2>
                <p class="mt-1.5 max-w-xl text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ __('Flashcards, semantic drills, and industrial quizzes extend LexiCraft Glossary with the same multilingual concept graph.') }}
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a
                        href="{{ route('learning.index', ['locale' => $locale]) }}"
                        wire:navigate
                        class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-3 py-2 text-xs font-medium text-zinc-800 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                    >
                        {{ __('Learning hub') }}
                    </a>
                    <a
                        href="{{ route('learning.flashcards', ['locale' => $locale]) }}"
                        wire:navigate
                        class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-3 py-2 text-xs font-medium text-zinc-800 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                    >
                        {{ __('Flashcards') }}
                    </a>
                    <a
                        href="{{ route('learning.semantic', ['locale' => $locale]) }}"
                        wire:navigate
                        class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-3 py-2 text-xs font-medium text-zinc-800 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                    >
                        {{ __('Semantic practice') }}
                    </a>
                    <a
                        href="{{ route('learning.quizzes', ['locale' => $locale]) }}"
                        wire:navigate
                        class="inline-flex items-center rounded-lg border border-zinc-300 bg-white px-3 py-2 text-xs font-medium text-zinc-800 transition-colors hover:bg-zinc-50 dark:border-zinc-600 dark:bg-zinc-900 dark:text-zinc-100 dark:hover:bg-zinc-800"
                    >
                        {{ __('Quizzes') }}
                    </a>
                </div>
            </div>
        </section>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
