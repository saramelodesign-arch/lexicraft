<?php

use Livewire\Component;

new class extends Component {
    //
}; ?>

<footer class="border-t border-zinc-200/90 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-950">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-7 lg:px-8">
        <div class="grid gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4 lg:gap-8">
            <div class="sm:col-span-2 lg:col-span-2">
                <p class="text-xs font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">{{ config('app.name') }}</p>
                <p class="mt-0.5 text-[10px] font-medium uppercase tracking-wider text-zinc-500 dark:text-zinc-500">{{ config('app.glossary_name') }}</p>
                <p class="mt-1.5 max-w-md text-[11px] leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ __('seo.footer_platform_description') }}
                </p>
            </div>

            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-500">{{ __('ui.glossary') }}</p>
                <nav class="mt-2 flex flex-col gap-1.5 text-[12px]" aria-label="{{ __('ui.glossary_navigation') }}">
                    <a href="{{ route('search', ['locale' => \App\Support\Locales::current()]) }}" wire:navigate class="text-zinc-600 transition-colors hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 focus-visible:ring-offset-2 dark:text-zinc-400 dark:hover:text-zinc-100 dark:focus-visible:ring-zinc-500 dark:focus-visible:ring-offset-zinc-950">{{ __('ui.search') }}</a>
                    <a href="{{ route('domains.index', ['locale' => \App\Support\Locales::current()]) }}" wire:navigate class="text-zinc-600 transition-colors hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 focus-visible:ring-offset-2 dark:text-zinc-400 dark:hover:text-zinc-100 dark:focus-visible:ring-zinc-500 dark:focus-visible:ring-offset-zinc-950">{{ __('ui.domains') }}</a>
                    <a href="{{ route('learning.index', ['locale' => \App\Support\Locales::current()]) }}" wire:navigate class="text-zinc-600 transition-colors hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 focus-visible:ring-offset-2 dark:text-zinc-400 dark:hover:text-zinc-100 dark:focus-visible:ring-zinc-500 dark:focus-visible:ring-offset-zinc-950">{{ __('ui.learning') }}</a>
                </nav>
            </div>

            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-500">{{ __('ui.language') }}</p>
                <nav class="mt-2 flex flex-col gap-1.5 text-[12px]" aria-label="{{ __('ui.language_selection') }}">
                    @foreach (\App\Support\Locales::supported() as $localeCode => $localeMeta)
                        <a
                            href="{{ \App\Support\Locales::localizedUrl($localeCode) }}"
                            wire:navigate
                            class="flex items-center justify-between gap-3 text-zinc-600 transition-colors hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 focus-visible:ring-offset-2 dark:text-zinc-400 dark:hover:text-zinc-100 dark:focus-visible:ring-zinc-500 dark:focus-visible:ring-offset-zinc-950"
                        >
                            <span>{{ $localeMeta['native'] }}</span>
                            <span class="font-mono text-[11px] font-medium tabular-nums text-zinc-400 dark:text-zinc-500">{{ strtoupper($localeCode) }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <div class="mt-5 flex flex-col gap-2 border-t border-zinc-200/90 pt-4 text-[11px] text-zinc-500 dark:border-zinc-800 dark:text-zinc-500 sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ now()->year }} {{ config('app.name') }}. {{ __('ui.all_rights_reserved') }}</p>
            <nav class="flex flex-wrap gap-x-4 gap-y-1" aria-label="{{ __('ui.legal_and_about') }}">
                <a href="{{ route('home', ['locale' => \App\Support\Locales::current()], absolute: false) }}#about-lexicraft" wire:navigate class="transition-colors hover:text-zinc-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 focus-visible:ring-offset-2 dark:hover:text-zinc-300 dark:focus-visible:ring-zinc-500 dark:focus-visible:ring-offset-zinc-950">{{ __('ui.about') }}</a>
                <span class="cursor-default">{{ __('ui.privacy') }}</span>
            </nav>
        </div>
    </div>
</footer>
