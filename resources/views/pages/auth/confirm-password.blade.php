<x-layouts::auth :title="__('auth_ui.confirm_password')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('auth_ui.confirm_password')"
            :description="__('auth_ui.confirm_password_description')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                :label="__('ui.password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('ui.password')"
                viewable
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
                {{ __('ui.confirm') }}
            </flux:button>
        </form>
    </div>
</x-layouts::auth>
