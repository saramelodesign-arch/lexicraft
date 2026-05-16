@extends('layouts.admin', ['pageTitle' => __('admin.new_domain')])

@section('subhead')
    {{ __('admin.domain_create_subhead') }}
@endsection

@section('content')
    <flux:card class="p-6">
        <form method="post" action="{{ route('admin.domains.store') }}" class="grid max-w-4xl gap-6">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <flux:input name="slug" value="{{ old('slug') }}" :label="__('admin.canonical_slug_internal')" required placeholder="footwear" />
                <div class="space-y-2">
                    <flux:label>{{ __('admin.parent_domain') }}</flux:label>
                    <select name="parent_id" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                        <option value="">{{ __('admin.top_level') }}</option>
                        @foreach ($parents as $p)
                            <option value="{{ $p->id }}" @selected(old('parent_id') == $p->id)>{{ $p->slug }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <flux:input name="icon" value="{{ old('icon') }}" :label="__('admin.icon_key_optional')" />
                <flux:input name="sort_order" type="number" value="{{ old('sort_order', 0) }}" :label="__('admin.sort_order')" />
            </div>
            <input type="hidden" name="is_active" value="0" />
            <flux:checkbox name="is_active" value="1" :label="__('admin.active')" :checked="(bool) old('is_active', true)" />

            <flux:separator />

            <flux:heading size="md">{{ __('admin.translations') }}</flux:heading>
            @foreach ($languages as $lang)
                @php $code = $lang->code; @endphp
                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-800">
                    <flux:text class="text-xs font-semibold uppercase tracking-wide text-zinc-500">{{ strtoupper($code) }}</flux:text>
                    <div class="mt-3 grid gap-3 sm:grid-cols-3">
                        <flux:input name="translations[{{ $code }}][name]" value="{{ old('translations.'.$code.'.name') }}" :label="__('admin.display_name')" required />
                        <flux:input name="translations[{{ $code }}][slug]" value="{{ old('translations.'.$code.'.slug') }}" :label="__('admin.url_slug')" required />
                        <div class="sm:col-span-3">
                            <flux:textarea name="translations[{{ $code }}][description]" rows="2" :label="__('admin.description')">{{ old('translations.'.$code.'.description') }}</flux:textarea>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex gap-2">
                <flux:button type="submit" variant="primary">{{ __('admin.create_domain') }}</flux:button>
                <flux:button variant="ghost" :href="route('admin.domains.index')">{{ __('admin.cancel') }}</flux:button>
            </div>
        </form>
    </flux:card>
@endsection
