<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('ui.primary_navigation')" class="grid">
                @can('access-admin')
                    <flux:sidebar.item icon="pencil-square" :href="route('admin.dashboard')" :current="request()->routeIs('admin.*')" wire:navigate>
                        {{ __('ui.editorial') }}
                    </flux:sidebar.item>
                @endcan
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('ui.dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="magnifying-glass" :href="route('search', ['locale' => \App\Support\Locales::current()])" wire:navigate>
                    {{ __('ui.search') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="size-6 lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('ui.profile') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('auth_ui.log_out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

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
