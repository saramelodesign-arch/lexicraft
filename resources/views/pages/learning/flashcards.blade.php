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
                {{ __('Flashcards') }}
            </h1>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Tap a card to reveal definitions, examples, domains, and semantic cues from the glossary graph.') }}
            </p>

            <div class="mt-8">
                <livewire:learning.flashcard-deck :locale="$locale" :domain-id="$domainId" :domain-slug="$domainSlug" />
            </div>
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
