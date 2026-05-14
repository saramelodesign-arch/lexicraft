<x-layouts::site
    :title="$pageTitle"
    :meta-description="$metaDescription"
    :canonical="$canonical"
    :og-url="$canonical"
    :structured-data="$structuredData"
    :json-ld-blocks="$jsonLdBlocks"
>
    @push('meta')
        @include('partials.hreflang-alternates', ['alternates' => $alternates, 'xDefaultUrl' => $xDefaultUrl])
    @endpush

    <livewire:layout.header />

    <main id="content" class="flex-1 px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl space-y-8">
            <div class="space-y-2 border-b border-zinc-200/90 pb-6 dark:border-zinc-800">
                <flux:link :href="route('home', ['locale' => $locale])" wire:navigate variant="subtle" class="text-[13px] text-zinc-600 dark:text-zinc-400">
                    ← {{ __('Back to home') }}
                </flux:link>
                @isset($breadcrumbs)
                    <div class="pt-2">
                        @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                    </div>
                @endisset
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                    {{ __('Navigation') }}
                </p>
                <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                    {{ __('Industrial domains') }}
                </h1>
                <p class="max-w-2xl text-[13px] leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ __('Structured taxonomy for multilingual glossary navigation. Counts reflect published concepts with a translation in the current language.') }}
                </p>
            </div>

            <section aria-labelledby="domain-tree-heading" class="space-y-3">
                <h2 id="domain-tree-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                    {{ __('Domain tree') }}
                </h2>
                @include('partials.domain-tree', ['domains' => $roots, 'locale' => $locale, 'depth' => 0])
            </section>
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
