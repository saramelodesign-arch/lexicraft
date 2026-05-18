@props([
    'label',
    'href' => null,
    'interactive' => true,
])

@php
    $chipClass = 'inline-flex items-center rounded-md border border-zinc-200 bg-zinc-50 px-2 py-0.5 text-[11px] font-medium leading-5 text-zinc-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300';
    $linkClass = 'hover:border-zinc-300 hover:text-zinc-900 dark:hover:border-zinc-500 dark:hover:text-zinc-100';
@endphp

@if (is_string($href) && $href !== '')
    <a href="{{ $href }}" wire:navigate @class([$chipClass, $linkClass => $interactive])>
        {{ $label }}
    </a>
@else
    <span @class([$chipClass])>{{ $label }}</span>
@endif

