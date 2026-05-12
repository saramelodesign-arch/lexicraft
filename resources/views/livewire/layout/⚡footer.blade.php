<?php

use Livewire\Component;

new class extends Component {
    //
}; ?>

<footer class="border-t border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-950">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4 lg:gap-10">
            <div class="sm:col-span-2 lg:col-span-2">
                <p class="text-[13px] font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">{{ config('app.name') }}</p>
                <p class="mt-2 max-w-md text-xs leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ __('Multilingual industrial terminology for footwear, leather goods, and manufacturing—structured for engineering, production, and quality teams.') }}
                </p>
            </div>

            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-500">{{ __('Glossary') }}</p>
                <nav class="mt-3 flex flex-col gap-2 text-[13px]" aria-label="{{ __('Glossary navigation') }}">
                    <a href="#" class="text-zinc-600 transition-colors hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">{{ __('Search') }}</a>
                    <a href="#domains-heading" class="text-zinc-600 transition-colors hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">{{ __('Domains') }}</a>
                    <a href="#learning-heading" class="text-zinc-600 transition-colors hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">{{ __('Learning') }}</a>
                </nav>
            </div>

            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-500">{{ __('Language') }}</p>
                <nav class="mt-3 flex flex-col gap-2 text-[13px]" aria-label="{{ __('Language selection') }}">
                    @foreach (config('locales.supported') as $localeCode => $localeMeta)
                        <a
                            href="{{ route('home', ['locale' => $localeCode]) }}"
                            wire:navigate
                            class="flex items-center justify-between gap-3 text-zinc-600 transition-colors hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100"
                        >
                            <span>{{ $localeMeta['native'] }}</span>
                            <span class="font-mono text-[11px] font-medium tabular-nums text-zinc-400 dark:text-zinc-500">{{ strtoupper($localeCode) }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <div class="mt-10 flex flex-col gap-3 border-t border-zinc-200/90 pt-6 text-[11px] text-zinc-500 dark:border-zinc-800 dark:text-zinc-500 sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ now()->year }} {{ config('app.name') }}. {{ __('All rights reserved.') }}</p>
            <nav class="flex flex-wrap gap-x-4 gap-y-1" aria-label="{{ __('Legal and about') }}">
                <a href="#" class="transition-colors hover:text-zinc-800 dark:hover:text-zinc-300">{{ __('About') }}</a>
                <a href="#" class="transition-colors hover:text-zinc-800 dark:hover:text-zinc-300">{{ __('Privacy') }}</a>
            </nav>
        </div>
    </div>
</footer>
