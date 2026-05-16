<x-layouts::app.sidebar :title="$title ?? null">
    <a
        href="#content"
        class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-md focus:bg-zinc-900 focus:px-3 focus:py-2 focus:text-xs focus:font-semibold focus:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-zinc-400 dark:focus:bg-zinc-100 dark:focus:text-zinc-900 dark:focus-visible:ring-zinc-500"
    >
        {{ __('ui.skip_to_content') }}
    </a>
    <flux:main id="content" tabindex="-1">
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
