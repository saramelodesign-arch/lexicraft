@extends('layouts.admin', ['pageTitle' => __('admin.domains')])

@section('subhead')
    {{ __('admin.domains_subhead') }}
@endsection

@section('content')
    <flux:card class="p-6">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <flux:input name="q" value="{{ $filters['q'] }}" :label="__('admin.search')" class="min-w-[200px]" />
            <flux:button type="submit" variant="primary">{{ __('admin.search') }}</flux:button>
            <flux:spacer />
            <flux:button variant="primary" :href="route('admin.domains.create')">{{ __('admin.new_domain') }}</flux:button>
        </form>
    </flux:card>

    <flux:card class="mt-8 overflow-x-auto p-0">
        <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
            <thead class="bg-zinc-50 text-left text-xs font-semibold uppercase text-zinc-500 dark:bg-zinc-900 dark:text-zinc-400">
                <tr>
                    <th class="px-4 py-3">{{ __('admin.url_slug') }}</th>
                    <th class="px-4 py-3">{{ __('admin.parent') }}</th>
                    <th class="px-4 py-3">{{ __('admin.concept_count') }}</th>
                    <th class="px-4 py-3">{{ __('admin.locale') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @foreach ($domains as $domain)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $domain->slug }}</td>
                        <td class="px-4 py-3 text-zinc-500">{{ $domain->parent?->slug ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $domain->concepts_count }}</td>
                        <td class="px-4 py-3 text-xs text-zinc-500">{{ $domain->translations->pluck('language.code')->filter()->map('strtoupper')->join(', ') }}</td>
                        <td class="px-4 py-3 text-end">
                            <flux:link :href="route('admin.domains.edit', $domain)" wire:navigate>{{ __('admin.edit') }}</flux:link>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="border-t border-zinc-200 px-4 py-3 dark:border-zinc-800">
            {{ $domains->links() }}
        </div>
    </flux:card>
@endsection
