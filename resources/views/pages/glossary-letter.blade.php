<x-layouts::site
    :title="$pageTitle"
    :meta-description="$metaDescription"
    :canonical="$canonical"
    :og-url="$canonical"
    :structured-data="null"
    :json-ld-blocks="$jsonLdBlocks"
    :robots-meta="$robotsMeta"
>
    @push('meta')
        @include('partials.hreflang-alternates', ['alternates' => $alternates, 'xDefaultUrl' => $xDefaultUrl])
    @endpush

    <livewire:layout.header />

    <main id="content" class="flex-1 px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">
            <flux:link :href="route('home', ['locale' => $locale])" wire:navigate variant="subtle" class="text-[13px] text-zinc-600 dark:text-zinc-400">
                ← {{ __('Back to home') }}
            </flux:link>

            <header class="mt-6 space-y-3">
                @isset($breadcrumbs)
                    @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                @endisset
                <div class="space-y-2">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                        {{ __('Multilingual glossary') }}
                    </p>
                    <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                        {{ $pageTitle }}
                    </h1>
                    <p class="max-w-2xl text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                        {{ __('Paginated terminology for the current locale, sorted alphabetically by translated term.') }}
                    </p>
                </div>
            </header>

            <div class="mt-10">
                <livewire:glossary.browse :letter="$letter" :locale="$locale" />
            </div>
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
