<x-layouts::app :title="__('ui.dashboard')">
    <div class="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-5">
        <section class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ __('ui.dashboard_welcome') }}</h1>
            <p class="mt-2 max-w-2xl text-sm text-zinc-600 dark:text-zinc-400">{{ __('ui.dashboard_description') }}</p>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <a href="{{ \App\Support\Locales::homeUrl() }}" wire:navigate class="rounded-xl border border-zinc-200 bg-white p-4 text-sm font-medium text-zinc-800 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800">
                {{ __('ui.dashboard_open_glossary') }}
            </a>
            <a href="{{ route('learning.index', ['locale' => \App\Support\Locales::current()], absolute: false) }}" wire:navigate class="rounded-xl border border-zinc-200 bg-white p-4 text-sm font-medium text-zinc-800 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800">
                {{ __('ui.dashboard_open_learning') }}
            </a>
            <a href="{{ route('profile.edit') }}" wire:navigate class="rounded-xl border border-zinc-200 bg-white p-4 text-sm font-medium text-zinc-800 transition-colors hover:bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-800">
                {{ __('ui.dashboard_open_profile') }}
            </a>
        </section>
    </div>
</x-layouts::app>
