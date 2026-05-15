<x-layouts::auth :title="__('auth_ui.log_in')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('auth_ui.login_title')" :description="__('auth_ui.login_description')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('ui.email_address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('ui.password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('ui.password')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('auth_ui.forgot_password_link') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('auth_ui.remember_me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('auth_ui.log_in') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('auth_ui.no_account') }}</span>
                <flux:link :href="route('register')" wire:navigate>{{ __('auth_ui.sign_up') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>
