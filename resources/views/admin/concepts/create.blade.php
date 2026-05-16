@extends('layouts.admin', ['pageTitle' => __('admin.new_concept')])

@section('subhead')
    {{ __('admin.create_first_translation_subhead') }}
@endsection

@section('content')
    <flux:card class="p-6">
        <form method="post" action="{{ route('admin.concepts.store') }}" class="grid max-w-3xl gap-6">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                    <flux:label>{{ __('admin.workflow_status') }}</flux:label>
                    <select name="status" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                        <option value="draft" @selected(old('status', 'draft') === 'draft')>{{ __('admin.draft') }}</option>
                        <option value="review" @selected(old('status') === 'review')>{{ __('admin.review') }}</option>
                        <option value="published" @selected(old('status') === 'published')>{{ __('admin.published') }}</option>
                        <option value="archived" @selected(old('status') === 'archived')>{{ __('admin.archived') }}</option>
                    </select>
                </div>
                <flux:input name="difficulty_level" value="{{ old('difficulty_level') }}" :label="__('admin.difficulty_level')" placeholder="{{ __('admin.difficulty_example') }}" />
            </div>
            <input type="hidden" name="is_featured" value="0" />
            <flux:checkbox name="is_featured" value="1" :label="__('admin.featured_concept')" :checked="(bool) old('is_featured', false)" />

            <div class="space-y-2">
                <flux:label>{{ __('admin.domains') }}</flux:label>
                <div class="grid gap-2 sm:grid-cols-2">
                    @foreach ($domains as $domain)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="domain_ids[]" value="{{ $domain->id }}" class="rounded border-zinc-300 dark:border-zinc-600" @checked(collect(old('domain_ids', []))->contains($domain->id)) />
                            {{ $domain->slug }}
                        </label>
                    @endforeach
                </div>
            </div>

            <flux:separator />

            <flux:heading size="md">{{ __('admin.initial_translation') }}</flux:heading>
            <div class="space-y-2">
                <flux:label>{{ __('admin.language') }}</flux:label>
                <select name="language_id" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    @foreach ($languages as $lang)
                        <option value="{{ $lang->id }}" @selected((string) old('language_id') === (string) $lang->id)>
                            {{ strtoupper($lang->code) }} — {{ $lang->native_name ?? $lang->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <flux:input name="term" value="{{ old('term') }}" :label="__('admin.term')" required />
            <flux:input name="slug" value="{{ old('slug') }}" :label="__('admin.url_slug')" required placeholder="lasting" />
            <div class="space-y-2">
                <flux:label>{{ __('admin.translation_status') }}</flux:label>
                <select name="translation_status" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    <option value="draft" @selected(old('translation_status', old('status', 'draft')) === 'draft')>{{ __('admin.draft') }}</option>
                    <option value="review" @selected(old('translation_status', old('status')) === 'review')>{{ __('admin.review') }}</option>
                    <option value="published" @selected(old('translation_status', old('status')) === 'published')>{{ __('admin.published') }}</option>
                    <option value="archived" @selected(old('translation_status', old('status')) === 'archived')>{{ __('admin.archived') }}</option>
                </select>
            </div>
            <flux:textarea name="short_definition" rows="3" :label="__('admin.short_definition')">{{ old('short_definition') }}</flux:textarea>
            <flux:textarea name="full_definition" rows="8" :label="__('admin.full_definition')">{{ old('full_definition') }}</flux:textarea>

            <div class="flex gap-2">
                <flux:button type="submit" variant="primary">{{ __('admin.create_concept') }}</flux:button>
                <flux:button variant="ghost" :href="route('admin.concepts.index')">{{ __('admin.cancel') }}</flux:button>
            </div>
        </form>
    </flux:card>
@endsection
