@extends('layouts.admin', ['pageTitle' => __('admin.edit_domain')])

@section('subhead')
    {{ __('admin.adjust_hierarchy_subhead') }}
@endsection

@section('content')
    <flux:card class="p-6">
        <form method="post" action="{{ route('admin.domains.update', $domain) }}" class="grid max-w-4xl gap-6">
            @csrf
            @method('PUT')
            <div class="grid gap-4 sm:grid-cols-2">
                <flux:input name="slug" value="{{ old('slug', $domain->slug) }}" :label="__('admin.canonical_slug')" required />
                <div class="space-y-2">
                    <flux:label>{{ __('admin.parent_domain') }}</flux:label>
                    <select name="parent_id" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                        <option value="">{{ __('admin.top_level') }}</option>
                        @foreach ($parents as $p)
                            <option value="{{ $p->id }}" @selected(old('parent_id', $domain->parent_id) == $p->id)>{{ $p->slug }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <flux:input name="icon" value="{{ old('icon', $domain->icon) }}" :label="__('admin.icon_key')" />
                <flux:input name="sort_order" type="number" value="{{ old('sort_order', $domain->sort_order) }}" :label="__('admin.sort_order')" />
            </div>
            <input type="hidden" name="is_active" value="0" />
            <flux:checkbox name="is_active" value="1" :label="__('admin.active')" :checked="(bool) old('is_active', $domain->is_active)" />

            <flux:separator />

            <flux:heading size="md">{{ __('admin.translations') }}</flux:heading>
            @foreach ($languages as $lang)
                @php
                    $code = $lang->code;
                    $tr = $domain->translations->firstWhere('language_id', $lang->id);
                @endphp
                <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-800">
                    <flux:text class="text-xs font-semibold uppercase tracking-wide text-zinc-500">{{ strtoupper($code) }}</flux:text>
                    <div class="mt-3 grid gap-3 sm:grid-cols-3">
                        <flux:input name="translations[{{ $code }}][name]" value="{{ old('translations.'.$code.'.name', $tr?->name) }}" :label="__('admin.display_name')" required />
                        <flux:input name="translations[{{ $code }}][slug]" value="{{ old('translations.'.$code.'.slug', $tr?->slug) }}" :label="__('admin.url_slug')" required />
                        <div class="sm:col-span-3">
                            <flux:textarea name="translations[{{ $code }}][description]" rows="2" :label="__('admin.description')">{{ old('translations.'.$code.'.description', $tr?->description) }}</flux:textarea>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex flex-wrap gap-2">
                <flux:button type="submit" variant="primary">{{ __('admin.save_domain') }}</flux:button>
                <flux:button variant="ghost" :href="route('admin.domains.index')">{{ __('admin.back') }}</flux:button>
            </div>
        </form>

        <form method="post" action="{{ route('admin.domains.destroy', $domain) }}" class="mt-8 border-t border-zinc-200 pt-6 dark:border-zinc-800" onsubmit="return confirm(@js(__('admin.delete_domain_confirm')))">
            @csrf
            @method('DELETE')
            <flux:button type="submit" variant="danger" size="sm">{{ __('admin.delete_domain') }}</flux:button>
        </form>
    </flux:card>
@endsection
