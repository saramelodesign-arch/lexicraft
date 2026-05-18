@php
    $pageHeading = $pageTitle ?? __('ui.editorial');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <title>{{ $pageHeading }} — {{ __('ui.editorial') }}</title>
    </head>
    <body class="min-h-screen bg-white font-[Inter,ui-sans-serif,system-ui,sans-serif] text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
        <a
            href="#content"
            class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-md focus:bg-zinc-900 focus:px-3 focus:py-2 focus:text-xs focus:font-semibold focus:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-zinc-400 dark:focus:bg-zinc-100 dark:focus:text-zinc-900 dark:focus-visible:ring-zinc-500"
        >
            {{ __('ui.skip_to_content') }}
        </a>
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <div class="flex flex-col gap-0.5 ps-1">
                    <flux:text class="text-[11px] font-semibold uppercase tracking-[0.12em] text-zinc-500 dark:text-zinc-400">
                        {{ config('app.name') }}
                    </flux:text>
                    <flux:heading size="lg" class="leading-tight">{{ __('ui.editorial') }}</flux:heading>
                </div>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('ui.primary_navigation')" class="grid">
                    <flux:sidebar.item icon="squares-2x2" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>
                        {{ __('admin.dash_title') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="book-open" :href="route('admin.concepts.index')" :current="request()->routeIs('admin.concepts.*')" wire:navigate>
                        {{ __('admin.concepts') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="rectangle-group" :href="route('admin.domains.index')" :current="request()->routeIs('admin.domains.*')" wire:navigate>
                        {{ __('admin.domains') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="photo" :href="route('admin.media.index')" :current="request()->routeIs('admin.media.*')" wire:navigate>
                        {{ __('admin.media_library') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="magnifying-glass-circle" :href="route('admin.seo.index')" :current="request()->routeIs('admin.seo.*')" wire:navigate>
                        {{ __('admin.seo_review') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('messages.settings')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" wire:navigate>
                        {{ __('ui.dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="arrow-top-right-on-square" :href="route('home', ['locale' => \App\Support\Locales::fallback()])" target="_blank">
                        {{ config('app.glossary_name') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="size-6 lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <flux:dropdown position="top" align="end">
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                            {{ __('auth_ui.log_out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:main id="content" tabindex="-1" class="min-h-screen bg-white dark:bg-zinc-950">
            <div class="border-b border-zinc-200 bg-white px-4 py-6 dark:border-zinc-800 dark:bg-zinc-950 sm:px-6 lg:px-10">
                <div class="mx-auto flex max-w-6xl flex-col gap-1">
                    <flux:heading size="xl">{{ $pageHeading }}</flux:heading>
                    @hasSection('subhead')
                        <flux:text class="max-w-3xl text-zinc-600 dark:text-zinc-400">@yield('subhead')</flux:text>
                    @endif
                </div>
            </div>
            <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-10">
                @if (session('status'))
                    <flux:callout variant="success" class="mb-6">
                        {{ session('status') }}
                    </flux:callout>
                @endif
                @if ($errors->any())
                    <flux:callout variant="danger" class="mb-6">
                        <ul class="list-inside list-disc text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </flux:callout>
                @endif
                @yield('content')
            </div>
        </flux:main>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
        <script>
            (() => {
                if (window.__lexicraftSearchShortcutBound) {
                    return;
                }
                window.__lexicraftSearchShortcutBound = true;

                const fallbackSearchUrl = @json(route('search', ['locale' => \App\Support\Locales::current()]));

                const isEditableTarget = (target) => {
                    if (!(target instanceof HTMLElement)) {
                        return false;
                    }

                    if (target.isContentEditable || target.closest('[contenteditable="true"]')) {
                        return true;
                    }

                    const tagName = target.tagName.toLowerCase();
                    if (['input', 'textarea', 'select'].includes(tagName)) {
                        return true;
                    }

                    return target.closest('input, textarea, select, [role="textbox"]') !== null;
                };

                const focusSearchInput = () => {
                    const el = document.querySelector('[data-search-focus]') ?? document.getElementById('global-search-input') ?? document.getElementById('results-search-input');
                    if (!(el instanceof HTMLElement)) {
                        return false;
                    }

                    el.focus();
                    if (typeof el.select === 'function') {
                        el.select();
                    }

                    return true;
                };

                document.addEventListener('keydown', (event) => {
                    if (event.defaultPrevented || event.key !== '/' || event.metaKey || event.ctrlKey || event.altKey) {
                        return;
                    }

                    if (isEditableTarget(event.target)) {
                        return;
                    }

                    event.preventDefault();
                    if (!focusSearchInput()) {
                        window.location.assign(fallbackSearchUrl);
                    }
                });
            })();
        </script>
    </body>
</html>
