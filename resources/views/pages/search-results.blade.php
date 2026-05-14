<x-layouts::site
    :title="$pageTitle"
    :meta-description="$metaDescription"
    :canonical="$canonical"
    :og-url="$canonical"
    :robots-meta="$robotsMeta"
>
    <livewire:layout.header />

    <main id="content" class="flex-1 px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">
            <livewire:search.results :locale="$locale" />
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
