<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <a
            href="#content"
            class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-md focus:bg-zinc-900 focus:px-3 focus:py-2 focus:text-xs focus:font-semibold focus:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-zinc-400 dark:focus:bg-zinc-100 dark:focus:text-zinc-900 dark:focus-visible:ring-zinc-500"
        >
            {{ __('ui.skip_to_content') }}
        </a>
        <main id="content" tabindex="-1" class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <div class="flex justify-end">
                    <flux:dropdown position="bottom" align="end">
                        <flux:button variant="ghost" size="sm" class="h-7 gap-1 rounded-md px-1.5 text-[11px] font-medium text-zinc-600 dark:text-zinc-400">
                            <flux:icon.language variant="micro" class="size-4" />
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
                </div>

                <a href="{{ \App\Support\Locales::homeUrl() }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                    </span>
                    <span class="sr-only">{{ config('app.name') }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </main>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
