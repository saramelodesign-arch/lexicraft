@props([
    'code',
    'active' => false,
    'size' => 'sm',
])

@php
    $sizeClass = $size === 'md'
        ? 'px-2 py-1 text-[11px]'
        : 'px-1.5 py-0.5 text-[10px]';
@endphp

<span {{ $attributes->class([
    'inline-flex items-center rounded-md border font-semibold uppercase tracking-wide',
    $sizeClass,
    'border-zinc-900 bg-zinc-900 text-white dark:border-zinc-100 dark:bg-zinc-100 dark:text-zinc-900' => $active,
    'border-zinc-300 bg-zinc-50 text-zinc-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300' => ! $active,
]) }}>
    {{ strtoupper((string) $code) }}
</span>

