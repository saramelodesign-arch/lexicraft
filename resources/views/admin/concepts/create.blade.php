@extends('layouts.admin', ['pageTitle' => __('New concept')])

@section('subhead')
    {{ __('Create the aggregate record and the first locale translation in one step. Add more locales from the editor.') }}
@endsection

@section('content')
    <flux:card class="p-6">
        <form method="post" action="{{ route('admin.concepts.store') }}" class="grid max-w-3xl gap-6">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="space-y-2">
                    <flux:label>{{ __('Workflow status') }}</flux:label>
                    <select name="status" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                        <option value="draft" @selected(old('status', 'draft') === 'draft')>{{ __('Draft') }}</option>
                        <option value="review" @selected(old('status') === 'review')>{{ __('Review') }}</option>
                        <option value="published" @selected(old('status') === 'published')>{{ __('Published') }}</option>
                        <option value="archived" @selected(old('status') === 'archived')>{{ __('Archived') }}</option>
                    </select>
                </div>
                <flux:input name="difficulty_level" value="{{ old('difficulty_level') }}" :label="__('Difficulty level')" placeholder="{{ __('e.g. beginner') }}" />
            </div>
            <input type="hidden" name="is_featured" value="0" />
            <flux:checkbox name="is_featured" value="1" :label="__('Featured concept')" :checked="(bool) old('is_featured', false)" />

            <div class="space-y-2">
                <flux:label>{{ __('Domains') }}</flux:label>
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

            <flux:heading size="md">{{ __('Initial translation') }}</flux:heading>
            <div class="space-y-2">
                <flux:label>{{ __('Language') }}</flux:label>
                <select name="language_id" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    @foreach ($languages as $lang)
                        <option value="{{ $lang->id }}" @selected((string) old('language_id') === (string) $lang->id)>
                            {{ strtoupper($lang->code) }} — {{ $lang->native_name ?? $lang->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <flux:input name="term" value="{{ old('term') }}" :label="__('Term')" required />
            <flux:input name="slug" value="{{ old('slug') }}" :label="__('URL slug')" required placeholder="lasting" />
            <div class="space-y-2">
                <flux:label>{{ __('Translation status') }}</flux:label>
                <select name="translation_status" required class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    <option value="draft" @selected(old('translation_status', old('status', 'draft')) === 'draft')>{{ __('Draft') }}</option>
                    <option value="review" @selected(old('translation_status', old('status')) === 'review')>{{ __('Review') }}</option>
                    <option value="published" @selected(old('translation_status', old('status')) === 'published')>{{ __('Published') }}</option>
                    <option value="archived" @selected(old('translation_status', old('status')) === 'archived')>{{ __('Archived') }}</option>
                </select>
            </div>
            <flux:textarea name="short_definition" rows="3" :label="__('Short definition')">{{ old('short_definition') }}</flux:textarea>
            <flux:textarea name="full_definition" rows="8" :label="__('Full definition')">{{ old('full_definition') }}</flux:textarea>

            <div class="flex gap-2">
                <flux:button type="submit" variant="primary">{{ __('Create concept') }}</flux:button>
                <flux:button variant="ghost" :href="route('admin.concepts.index')">{{ __('Cancel') }}</flux:button>
            </div>
        </form>
    </flux:card>
@endsection
