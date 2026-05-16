<x-layouts::site
    :title="$pageTitle"
    :meta-description="$metaDescription"
    :canonical="$canonical"
    :json-ld-blocks="$jsonLdBlocks"
>
    @push('meta')
        @include('partials.hreflang-alternates', ['alternates' => $alternates, 'xDefaultUrl' => $xDefaultUrl])
    @endpush

    <livewire:layout.header />

    <main id="content" class="flex-1">
        <div class="border-b border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-zinc-500 dark:text-zinc-400">
                    {{ __('learning.lab') }}
                </p>
                <h1 class="mt-2 text-balance text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                    {{ __('learning.industrial_learning') }}
                </h1>
                <p class="mt-3 max-w-2xl text-pretty text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ __('learning.hub_intro') }}
                </p>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <a
                    href="{{ route('learning.flashcards', ['locale' => $locale]) }}"
                    wire:navigate
                    class="block rounded-xl border border-zinc-200 bg-zinc-50 p-5 transition-colors hover:border-zinc-300 hover:bg-white dark:border-zinc-800 dark:bg-zinc-900/50 dark:hover:border-zinc-600"
                >
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('ui.flashcards') }}</h2>
                    <p class="mt-2 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                        {{ __('learning.flashcards_card_description') }}
                    </p>
                </a>
                <a
                    href="{{ route('learning.semantic', ['locale' => $locale]) }}"
                    wire:navigate
                    class="block rounded-xl border border-zinc-200 bg-zinc-50 p-5 transition-colors hover:border-zinc-300 hover:bg-white dark:border-zinc-800 dark:bg-zinc-900/50 dark:hover:border-zinc-600"
                >
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('ui.semantic_practice') }}</h2>
                    <p class="mt-2 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                        {{ __('learning.semantic_card_description') }}
                    </p>
                </a>
                <a
                    href="{{ route('learning.quizzes', ['locale' => $locale]) }}"
                    wire:navigate
                    class="block rounded-xl border border-zinc-200 bg-zinc-50 p-5 transition-colors hover:border-zinc-300 hover:bg-white dark:border-zinc-800 dark:bg-zinc-900/50 dark:hover:border-zinc-600"
                >
                    <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ __('ui.quizzes') }}</h2>
                    <p class="mt-2 text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                        {{ __('learning.quizzes_card_description') }}
                    </p>
                </a>
            </div>

            @auth
                <p class="mt-8 text-sm text-zinc-600 dark:text-zinc-400">
                    <flux:link :href="route('learning.progress', ['locale' => $locale])" wire:navigate class="font-medium">
                        {{ __('learning.view_progress') }}
                    </flux:link>
                </p>
            @endauth
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
