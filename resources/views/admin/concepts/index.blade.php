@extends('layouts.admin', ['pageTitle' => __('admin.concepts')])

@section('subhead')
    {{ __('admin.search_subhead') }}
@endsection

@section('content')
    <flux:card class="p-6">
        <form method="get" action="{{ route('admin.concepts.index') }}" class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <flux:input name="q" value="{{ $filters['q'] }}" :label="__('admin.search')" placeholder="{{ __('admin.term_slug_snippet') }}" />
            <div class="space-y-2">
                <flux:label>{{ __('admin.status') }}</flux:label>
                <select name="status" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    <option value="">{{ __('admin.any') }}</option>
                    <option value="draft" @selected($filters['status'] === 'draft')>{{ __('admin.draft') }}</option>
                    <option value="review" @selected($filters['status'] === 'review')>{{ __('admin.review') }}</option>
                    <option value="published" @selected($filters['status'] === 'published')>{{ __('admin.published') }}</option>
                    <option value="archived" @selected($filters['status'] === 'archived')>{{ __('admin.archived') }}</option>
                </select>
            </div>
            <div class="space-y-2">
                <flux:label>{{ __('admin.domain_filter') }}</flux:label>
                <select name="domain_id" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    <option value="">{{ __('admin.any_domain') }}</option>
                    @foreach ($domains as $d)
                        <option value="{{ $d->id }}" @selected((int) ($filters['domain_id'] ?? 0) === $d->id)>{{ $d->slug }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <flux:label>{{ __('admin.missing_locale') }}</flux:label>
                <select name="missing_locale" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    <option value="">{{ __('admin.top_level') }}</option>
                    @foreach ($languages as $lang)
                        <option value="{{ $lang->code }}" @selected($filters['missing_locale'] === $lang->code)>{{ strtoupper($lang->code) }} — {{ $lang->native_name ?? $lang->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2 md:col-span-2 lg:col-span-4">
                <flux:button type="submit" variant="primary">{{ __('admin.apply_filters') }}</flux:button>
                <flux:button variant="ghost" :href="route('admin.concepts.index')">{{ __('admin.reset') }}</flux:button>
                <flux:spacer />
                <flux:button variant="primary" :href="route('admin.concepts.create')">{{ __('admin.new_concept') }}</flux:button>
            </div>
        </form>
    </flux:card>

    <flux:card class="mt-8 overflow-x-auto p-0">
        <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
            <thead class="bg-zinc-50 text-left text-xs font-semibold uppercase text-zinc-500 dark:bg-zinc-900 dark:text-zinc-400">
                <tr>
                    <th class="px-4 py-3">{{ __('admin.id') }}</th>
                    <th class="px-4 py-3">{{ __('admin.status') }}</th>
                    <th class="px-4 py-3">{{ __('admin.coverage') }}</th>
                    <th class="px-4 py-3">{{ __('admin.published_locales') }}</th>
                    <th class="px-4 py-3">{{ __('admin.labels') }}</th>
                    <th class="px-4 py-3">{{ __('admin.warnings') }}</th>
                    <th class="px-4 py-3">{{ __('admin.updated') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @foreach ($concepts as $concept)
                    <tr class="hover:bg-zinc-50/80 dark:hover:bg-zinc-900/40">
                        <td class="whitespace-nowrap px-4 py-3 font-mono text-xs">{{ $concept->id }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-zinc-200 px-2 py-0.5 text-xs font-medium dark:bg-zinc-700">{{ ucfirst($concept->status) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            {{ $concept->translations_count }} / {{ $languages->count() }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $concept->published_translations_count }} / {{ $languages->count() }}
                        </td>
                        <td class="px-4 py-3">
                            <flux:link :href="route('admin.concepts.edit', $concept)" wire:navigate class="font-medium">
                                {{ $concept->translations->pluck('term')->take(3)->join(' · ') ?: '—' }}
                            </flux:link>
                        </td>
                        <td class="px-4 py-3 text-xs text-zinc-500">
                            @if ($concept->status === 'published' && $concept->published_translations_count === 0)
                                {{ __('admin.no_published_translation') }}
                            @elseif ($concept->translations_count < $languages->count())
                                {{ __('admin.missing_locales') }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-zinc-500">{{ $concept->updated_at?->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="border-t border-zinc-200 px-4 py-3 dark:border-zinc-800">
            {{ $concepts->links() }}
        </div>
    </flux:card>
@endsection
