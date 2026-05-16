@props([
    'items' => [],
])

@if (count($items) > 0)
    <nav aria-label="{{ __('messages.breadcrumb') }}" class="flex flex-wrap items-center gap-x-1.5 gap-y-1 text-[12px] text-zinc-600 dark:text-zinc-400">
        @foreach ($items as $index => $item)
            @if ($index > 0)
                <span class="text-zinc-400" aria-hidden="true">/</span>
            @endif
            @if (! empty($item['url']) && $index < count($items) - 1)
                <flux:link href="{{ $item['url'] }}" wire:navigate variant="subtle" class="text-[12px]">
                    {{ $item['name'] }}
                </flux:link>
            @else
                <span @class(['font-medium text-zinc-900 dark:text-zinc-100' => $index === count($items) - 1])>{{ $item['name'] }}</span>
            @endif
        @endforeach
    </nav>
@endif
