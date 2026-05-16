@extends('layouts.admin', ['pageTitle' => __('admin.seo_review')])

@section('subhead')
    {{ __('admin.dash_missing_og') }}
@endsection

@section('content')
    <flux:card class="p-6">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="space-y-2">
                <flux:label>{{ __('admin.locale_filter') }}</flux:label>
                <select name="locale" class="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    <option value="">{{ __('admin.all') }}</option>
                    @foreach (\App\Support\Locales::codes() as $code)
                        <option value="{{ $code }}" @selected($locale === $code)>{{ strtoupper($code) }}</option>
                    @endforeach
                </select>
            </div>
            <flux:button type="submit" variant="primary">{{ __('admin.apply') }}</flux:button>
        </form>
    </flux:card>

    <flux:card class="mt-8 overflow-x-auto p-0">
        <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
            <thead class="bg-zinc-50 text-left text-xs font-semibold uppercase text-zinc-500 dark:bg-zinc-900 dark:text-zinc-400">
                <tr>
                    <th class="px-4 py-3">{{ __('admin.term') }}</th>
                    <th class="px-4 py-3">{{ __('admin.locale') }}</th>
                    <th class="px-4 py-3">{{ __('admin.missing') }}</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse ($translations as $tr)
                    @php
                        $missing = [];
                        if (trim((string) $tr->seo_title) === '') {
                            $missing[] = __('admin.title');
                        }
                        if (trim((string) $tr->seo_description) === '') {
                            $missing[] = __('admin.description');
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
                                {{ __('admin.edit') }}
                            </flux:link>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-sm text-zinc-500">{{ __('admin.no_gaps_for_filter') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="border-t border-zinc-200 px-4 py-3 dark:border-zinc-800">
            {{ $translations->links() }}
        </div>
    </flux:card>
@endsection
