@extends('layouts.admin', ['pageTitle' => __('SEO review')])

@section('subhead')
    {{ __('Published translations missing title, description, or Open Graph headline — open each concept to complete metadata.') }}
@endsection

@section('content')
    <flux:card class="p-6">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="space-y-2">
                <flux:label>{{ __('Locale filter') }}</flux:label>
                <select name="locale" class="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    <option value="">{{ __('All') }}</option>
                    @foreach (\App\Support\Locales::codes() as $code)
                        <option value="{{ $code }}" @selected($locale === $code)>{{ strtoupper($code) }}</option>
                    @endforeach
                </select>
            </div>
            <flux:button type="submit" variant="primary">{{ __('Apply') }}</flux:button>
        </form>
    </flux:card>

    <flux:card class="mt-8 overflow-x-auto p-0">
        <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
            <thead class="bg-zinc-50 text-left text-xs font-semibold uppercase text-zinc-500 dark:bg-zinc-900 dark:text-zinc-400">
                <tr>
                    <th class="px-4 py-3">{{ __('Term') }}</th>
                    <th class="px-4 py-3">{{ __('Locale') }}</th>
                    <th class="px-4 py-3">{{ __('Missing') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse ($translations as $tr)
                    @php
                        $missing = [];
                        if (trim((string) $tr->seo_title) === '') {
                            $missing[] = __('title');
                        }
                        if (trim((string) $tr->seo_description) === '') {
                            $missing[] = __('description');
                        }
                        if (trim((string) $tr->og_title) === '') {
                            $missing[] = 'OG';
                        }
                    @endphp
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $tr->term }}</td>
                        <td class="px-4 py-3 uppercase text-zinc-500">{{ $tr->language?->code }}</td>
                        <td class="px-4 py-3 text-sm text-amber-700 dark:text-amber-400">{{ implode(', ', $missing) }}</td>
                        <td class="px-4 py-3 text-end">
                            @php
                                $lc = $tr->language?->code ?? '';
                                $editUrl = route('admin.concepts.edit', $tr->concept_id).($lc !== '' ? '#locale-'.$lc : '');
                            @endphp
                            <flux:link :href="$editUrl" wire:navigate>
                                {{ __('Edit') }}
                            </flux:link>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-sm text-zinc-500">{{ __('No gaps in this filter.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="border-t border-zinc-200 px-4 py-3 dark:border-zinc-800">
            {{ $translations->links() }}
        </div>
    </flux:card>
@endsection
