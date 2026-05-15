<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('auth_ui.create_account_title')" :description="__('auth_ui.create_account_description')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('ui.name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('ui.full_name')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('ui.email_address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <flux:select name="preferred_locale" :label="__('ui.preferred_language')" required>
                @foreach (\App\Support\Locales::supported() as $localeCode => $localeMeta)
                    <option value="{{ $localeCode }}" @selected(old('preferred_locale', app()->getLocale()) === $localeCode)>
                        {{ $localeMeta['native'] }}
                    </option>
                @endforeach
            </flux:select>

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('ui.password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('ui.password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('ui.confirm_password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('ui.confirm_password')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('auth_ui.create_account_cta') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('auth_ui.already_have_account') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('auth_ui.log_in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
