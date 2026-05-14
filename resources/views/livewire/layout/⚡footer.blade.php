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
                <p class="mt-1.5 max-w-md text-[11px] leading-relaxed text-zinc-600 dark:text-zinc-400">
                    {{ __('Multilingual industrial terminology for footwear, leather goods, and manufacturing—structured for engineering, production, and quality teams.') }}
                </p>
            </div>

            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-500">{{ __('Glossary') }}</p>
                <nav class="mt-2 flex flex-col gap-1.5 text-[12px]" aria-label="{{ __('Glossary navigation') }}">
                    <a href="#site-search" class="text-zinc-600 transition-colors hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">{{ __('Search') }}</a>
                    <a href="{{ route('domains.index', ['locale' => \App\Support\Locales::current()]) }}" wire:navigate class="text-zinc-600 transition-colors hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">{{ __('Domains') }}</a>
                    <a href="{{ route('learning.index', ['locale' => \App\Support\Locales::current()]) }}" wire:navigate class="text-zinc-600 transition-colors hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-zinc-100">{{ __('Learning') }}</a>
                </nav>
            </div>

            <div>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-500">{{ __('Language') }}</p>
                <nav class="mt-2 flex flex-col gap-1.5 text-[12px]" aria-label="{{ __('Language selection') }}">
                    @foreach (\App\Support\Locales::supported() as $localeCode => $localeMeta)
                        <a
                            href="{{ \App\Support\Locales::localizedUrl($localeCode) }}"
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

        <div class="mt-5 flex flex-col gap-2 border-t border-zinc-200/90 pt-4 text-[11px] text-zinc-500 dark:border-zinc-800 dark:text-zinc-500 sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ now()->year }} {{ config('app.name') }}. {{ __('All rights reserved.') }}</p>
            <nav class="flex flex-wrap gap-x-4 gap-y-1" aria-label="{{ __('Legal and about') }}">
                <a href="#" class="transition-colors hover:text-zinc-800 dark:hover:text-zinc-300">{{ __('About') }}</a>
                <a href="#" class="transition-colors hover:text-zinc-800 dark:hover:text-zinc-300">{{ __('Privacy') }}</a>
            </nav>
        </div>
    </div>
</footer>
