<x-layouts::auth :title="__('auth_ui.forgot_password')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('auth_ui.forgot_password')" :description="__('auth_ui.forgot_password_description')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('ui.email_address')"
                type="email"
                required
                autofocus
                :placeholder="__('ui.email_placeholder')"
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                {{ __('auth_ui.email_reset_link') }}
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>{{ __('auth_ui.or_return_to') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('auth_ui.log_in_lower') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
