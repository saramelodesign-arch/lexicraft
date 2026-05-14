<x-layouts::site
    :title="$pageTitle"
    :meta-description="$metaDescription"
    :canonical="$canonical"
    :robots-meta="$robotsMeta ?? null"
>
    <livewire:layout.header />

    <main id="content" class="flex-1">
        <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
            <flux:link href="{{ route('learning.index', ['locale' => $locale]) }}" wire:navigate variant="subtle" class="text-[13px] text-zinc-600 dark:text-zinc-400">
                ← {{ __('Learning hub') }}
            </flux:link>
            <h1 class="mt-4 text-xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                {{ __('Semantic practice') }}
            </h1>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Each prompt is generated from published relations—synonyms, broader/narrower, and related concepts.') }}
            </p>

            <div class="mt-8">
                <livewire:learning.semantic-practice :locale="$locale" />
            </div>
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
