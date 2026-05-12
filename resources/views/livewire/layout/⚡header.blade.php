<?php

use Livewire\Component;

new class extends Component {
    //
}; ?>

<header
    class="sticky top-0 z-50 border-b border-zinc-200/90 bg-zinc-50/90 backdrop-blur-md dark:border-zinc-800/90 dark:bg-zinc-950/90"
>
    <div class="mx-auto flex h-11 max-w-7xl items-center gap-2 px-4 sm:h-11 sm:gap-3 sm:px-6 lg:px-8">
        <a
            href="{{ route('home') }}"
            class="group flex shrink-0 items-center gap-2 rounded-md py-1 pe-2 ps-1 text-zinc-900 transition-colors hover:bg-zinc-200/60 dark:text-zinc-100 dark:hover:bg-zinc-800/60"
            wire:navigate
        >
            <span class="flex size-7 items-center justify-center rounded border border-zinc-200 bg-white text-zinc-700 shadow-[0_1px_0_0_rgba(0,0,0,0.04)] dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-200">
                <x-app-logo-icon class="size-4 text-zinc-600 dark:text-zinc-300" />
            </span>
            <span class="text-[13px] font-semibold tracking-tight text-zinc-800 dark:text-zinc-100">{{ config('app.name') }}</span>
        </a>

        <nav class="hidden items-center gap-0.5 lg:flex" aria-label="{{ __('Primary') }}">
            @foreach (
                [
                    ['label' => __('Glossary'), 'href' => '#'],
                    ['label' => __('Domains'), 'href' => '#domains-heading'],
                    ['label' => __('Learning'), 'href' => '#learning-heading'],
                    ['label' => __('About'), 'href' => '#'],
                ]
                as $item
            )
                <a
                    href="{{ $item['href'] }}"
                    class="rounded-md px-2.5 py-1.5 text-[13px] font-medium text-zinc-600 transition-colors hover:bg-zinc-200/70 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/70 dark:hover:text-zinc-100"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <flux:spacer />

        <flux:dropdown position="bottom" align="end" class="lg:hidden">
            <flux:button variant="ghost" size="sm" icon="bars-2" class="size-8 text-zinc-600 dark:text-zinc-400" />

            <flux:menu>
                <flux:menu.item href="#">{{ __('Glossary') }}</flux:menu.item>
                <flux:menu.item href="#domains-heading">{{ __('Domains') }}</flux:menu.item>
                <flux:menu.item href="#learning-heading">{{ __('Learning') }}</flux:menu.item>
                <flux:menu.item href="#">{{ __('About') }}</flux:menu.item>
            </flux:menu>
        </flux:dropdown>

        <flux:dropdown position="bottom" align="end">
            <flux:button
                variant="ghost"
                size="sm"
                class="h-8 gap-1.5 rounded-md border border-transparent px-2 text-[12px] font-medium text-zinc-600 hover:border-zinc-200 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:border-zinc-700 dark:hover:bg-zinc-900"
            >
                <flux:icon.language variant="micro" class="text-zinc-500 dark:text-zinc-500" />
                <span>{{ strtoupper(\Illuminate\Support\Str::before(app()->getLocale(), '_')) }}</span>
            </flux:button>

            <flux:menu>
                @foreach (config('locales.supported') as $localeCode => $localeMeta)
                    <flux:menu.item :href="route('home', ['locale' => $localeCode])" wire:navigate>
                        <span class="flex min-w-[11rem] items-center justify-between gap-3">
                            <span>{{ $localeMeta['native'] }}</span>
                            <span class="font-mono text-[11px] font-medium tabular-nums text-zinc-400 dark:text-zinc-500">{{ strtoupper($localeCode) }}</span>
                        </span>
                    </flux:menu.item>
                @endforeach
            </flux:menu>
        </flux:dropdown>

        <div class="hidden items-center rounded-md border border-zinc-200/80 bg-zinc-100/50 p-0.5 dark:border-zinc-800 dark:bg-zinc-900/50 sm:flex">
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun" title="{{ __('Light') }}" />
                <flux:radio value="dark" icon="moon" title="{{ __('Dark') }}" />
                <flux:radio value="system" icon="computer-desktop" title="{{ __('System') }}" />
            </flux:radio.group>
        </div>

        @if (Route::has('login'))
            <div class="flex items-center gap-1.5 ps-0.5">
                @auth
                    <flux:button :href="route('dashboard')" variant="primary" size="sm" wire:navigate class="h-8 px-3 text-[13px] font-medium">
                        {{ __('Dashboard') }}
                    </flux:button>
                @else
                    <flux:button :href="route('login')" variant="ghost" size="sm" wire:navigate class="h-8 px-2.5 text-[13px] font-medium text-zinc-700 dark:text-zinc-300">
                        {{ __('Log in') }}
                    </flux:button>
                    @if (Route::has('register'))
                        <flux:button :href="route('register')" variant="primary" size="sm" wire:navigate class="hidden h-8 px-3 text-[13px] font-medium sm:inline-flex">
                            {{ __('Register') }}
                        </flux:button>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</header>
