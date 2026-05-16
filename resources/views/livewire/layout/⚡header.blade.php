<?php

use Livewire\Component;

new class extends Component {
    //
}; ?>

<header
    class="sticky top-0 z-50 border-b border-zinc-200/90 bg-zinc-50/90 backdrop-blur-md dark:border-zinc-800/90 dark:bg-zinc-950/90"
>
    <div class="mx-auto flex h-11 max-w-7xl items-center gap-1.5 px-4 sm:gap-2 sm:px-5 lg:px-6">
        <a
            href="{{ \App\Support\Locales::homeUrl() }}"
            class="group flex shrink-0 items-center gap-1.5 rounded-md py-0.5 pe-1.5 ps-0.5 text-zinc-900 transition-colors hover:bg-zinc-200/60 dark:text-zinc-100 dark:hover:bg-zinc-800/60"
            wire:navigate
        >
            <span class="flex size-6 items-center justify-center rounded border border-zinc-200 bg-white text-zinc-700 shadow-[0_1px_0_0_rgba(0,0,0,0.04)] dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">
                <x-app-logo-icon class="size-4 text-zinc-600 dark:text-zinc-300" />
            </span>
            <span class="text-[12px] font-semibold tracking-tight text-zinc-800 dark:text-zinc-100">{{ config('app.name') }}</span>
        </a>

        <nav class="hidden items-center gap-0 lg:flex" aria-label="{{ __('ui.primary_navigation') }}">
            @php
                $currentLocale = \App\Support\Locales::current();
                $domainsIndex = route('domains.index', ['locale' => $currentLocale], absolute: false);
                $searchIndex = route('search', ['locale' => $currentLocale], absolute: false);
                $learningIndex = route('learning.index', ['locale' => $currentLocale], absolute: false);
                $homeIndex = route('home', ['locale' => $currentLocale], absolute: false);
                $aboutHref = $homeIndex.'#about-lexicraft';
            @endphp
                @foreach (
                    [
                        ['label' => __('ui.glossary'), 'href' => $searchIndex],
                        ['label' => __('ui.domains'), 'href' => $domainsIndex],
                        ['label' => __('ui.learning'), 'href' => $learningIndex],
                        ['label' => __('ui.about'), 'href' => $aboutHref],
                    ]
                        as $item
                )
                <a
                    href="{{ $item['href'] }}"
                    class="rounded-md px-2 py-1 text-[12px] font-medium text-zinc-600 transition-colors hover:bg-zinc-200/70 hover:text-zinc-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 focus-visible:ring-offset-2 dark:text-zinc-400 dark:hover:bg-zinc-800/70 dark:hover:text-zinc-100 dark:focus-visible:ring-zinc-500 dark:focus-visible:ring-offset-zinc-950"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <flux:spacer />

        <flux:dropdown position="bottom" align="end" class="lg:hidden">
            <flux:button variant="ghost" size="sm" icon="bars-2" class="size-6 text-zinc-600 dark:text-zinc-400" />

            <flux:menu>
                <flux:menu.item :href="route('search', ['locale' => \App\Support\Locales::current()])" wire:navigate>{{ __('ui.glossary') }}</flux:menu.item>
                <flux:menu.item :href="route('domains.index', ['locale' => \App\Support\Locales::current()])" wire:navigate>{{ __('ui.domains') }}</flux:menu.item>
                <flux:menu.item :href="route('learning.index', ['locale' => \App\Support\Locales::current()])" wire:navigate>{{ __('ui.learning') }}</flux:menu.item>
                <flux:menu.item :href="route('home', ['locale' => \App\Support\Locales::current()], absolute: false).'#about-lexicraft'" wire:navigate>{{ __('ui.about') }}</flux:menu.item>
            </flux:menu>
        </flux:dropdown>

        <flux:dropdown position="bottom" align="end">
            <flux:button
                variant="ghost"
                size="sm"
                class="h-7 gap-1 rounded-md border border-transparent px-1.5 text-[11px] font-medium text-zinc-600 hover:border-zinc-200 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:border-zinc-700 dark:hover:bg-zinc-900"
            >
                <flux:icon.language variant="micro" class="size-4 text-zinc-500 dark:text-zinc-500" />
                <span>{{ strtoupper(\Illuminate\Support\Str::before(app()->getLocale(), '_')) }}</span>
            </flux:button>

            <flux:menu>
                @foreach (\App\Support\Locales::supported() as $localeCode => $localeMeta)
                    <flux:menu.item :href="\App\Support\Locales::localizedUrl($localeCode)" wire:navigate>
                        <span class="flex min-w-[11rem] items-center justify-between gap-3">
                            <span>{{ $localeMeta['native'] }}</span>
                            <span class="font-mono text-[11px] font-medium tabular-nums text-zinc-400 dark:text-zinc-500">{{ strtoupper($localeCode) }}</span>
                        </span>
                    </flux:menu.item>
                @endforeach
            </flux:menu>
        </flux:dropdown>

        <div class="hidden origin-right scale-[0.78] items-center rounded-md border border-zinc-200/60 bg-zinc-100/35 p-px dark:border-zinc-800/80 dark:bg-zinc-900/35 sm:flex">
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun" title="{{ __('ui.theme_light') }}" />
                <flux:radio value="dark" icon="moon" title="{{ __('ui.theme_dark') }}" />
                <flux:radio value="system" icon="computer-desktop" title="{{ __('ui.theme_system') }}" />
            </flux:radio.group>
        </div>

        @if (Route::has('login'))
            <div class="flex items-center gap-1 ps-0.5">
                @auth
                    <flux:button :href="route('dashboard')" variant="outline" size="sm" wire:navigate class="h-7 border-zinc-200 px-2.5 text-[12px] font-medium text-zinc-800 dark:border-zinc-700 dark:text-zinc-200">
                        {{ __('ui.dashboard') }}
                    </flux:button>
                @else
                    <flux:button
                        :href="route('login')"
                        variant="ghost"
                        size="sm"
                        wire:navigate
                        class="h-7 px-2 text-[12px] font-normal text-zinc-600 hover:bg-transparent hover:text-zinc-900 hover:underline dark:text-zinc-400 dark:hover:text-zinc-100"
                    >
                        {{ __('auth_ui.log_in') }}
                    </flux:button>
                    @if (Route::has('register'))
                        <flux:button
                            :href="route('register')"
                            variant="primary"
                            size="sm"
                            wire:navigate
                            class="hidden h-7 border-0 bg-zinc-900 px-3 text-[12px] font-medium text-white shadow-none hover:bg-zinc-800 sm:inline-flex dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            {{ __('auth_ui.register') }}
                        </flux:button>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</header>
