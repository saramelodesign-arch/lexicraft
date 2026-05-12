<x-layouts::site
    :title="__('Glossary').' — '.$letter"
    :meta-description="__('Browse terms starting with :letter.', ['letter' => $letter])"
    :canonical="url()->current()"
>
    <livewire:layout.header />

    <main class="flex-1 px-4 py-12 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl">
            <flux:link :href="route('home')" wire:navigate variant="subtle" class="text-[13px] text-zinc-600 dark:text-zinc-400">
                ← {{ __('Back to home') }}
            </flux:link>
            <h1 class="mt-6 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                {{ __('Glossary: letter :letter', ['letter' => $letter]) }}
            </h1>
            <p class="mt-2 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                {{ __('Stub view—term listings will load from the database in a later step.') }}
            </p>
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
