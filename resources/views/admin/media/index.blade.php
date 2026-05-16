@extends('layouts.admin', ['pageTitle' => __('admin.media_library')])

@section('subhead')
    {{ __('admin.media_library_subhead') }}
@endsection

@section('content')
    <flux:card class="overflow-x-auto p-0">
        <table class="min-w-full divide-y divide-zinc-200 text-sm dark:divide-zinc-800">
            <thead class="bg-zinc-50 text-left text-xs font-semibold uppercase text-zinc-500 dark:bg-zinc-900 dark:text-zinc-400">
                <tr>
                    <th class="px-4 py-3">{{ __('admin.preview') }}</th>
                    <th class="px-4 py-3">{{ __('admin.file') }}</th>
                    <th class="px-4 py-3">{{ __('admin.concept') }}</th>
                    <th class="px-4 py-3">{{ __('admin.collection') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @foreach ($media as $m)
                    <tr>
                        <td class="px-4 py-3">
                            @if (str_starts_with((string) $m->mime_type, 'image/'))
                                <img src="{{ $m->hasGeneratedConversion('thumb') ? $m->getUrl('thumb') : $m->getUrl() }}" alt="" class="h-12 w-12 rounded object-cover" />
                            @else
                                <span class="text-xs text-zinc-400">{{ __('admin.file') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $m->name }}</div>
                            <div class="text-xs text-zinc-500">{{ $m->mime_type }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @if ($m->model)
                                <flux:link :href="route('admin.concepts.edit', $m->model_id)" wire:navigate>#{{ $m->model_id }}</flux:link>
                                <span class="text-xs text-zinc-500">({{ $m->model->status ?? '—' }})</span>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $m->collection_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="border-t border-zinc-200 px-4 py-3 dark:border-zinc-800">
            {{ $media->links() }}
        </div>
    </flux:card>
@endsection
