@props([
    'label',
    'status' => 'default',
    'size' => 'sm',
])

@php
    $sizeClass = $size === 'md'
        ? 'px-2 py-1 text-[11px]'
        : 'px-1.5 py-0.5 text-[10px]';

    $toneMap = [
        'draft' => 'border-zinc-300 bg-zinc-100 text-zinc-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300',
        'review' => 'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-700 dark:bg-amber-950/40 dark:text-amber-300',
        'reviewed' => 'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-700 dark:bg-amber-950/40 dark:text-amber-300',
        'validated' => 'border-emerald-300 bg-emerald-50 text-emerald-800 dark:border-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300',
        'regional' => 'border-sky-300 bg-sky-50 text-sky-800 dark:border-sky-700 dark:bg-sky-950/40 dark:text-sky-300',
        'deprecated' => 'border-rose-300 bg-rose-50 text-rose-800 dark:border-rose-700 dark:bg-rose-950/40 dark:text-rose-300',
        'published' => 'border-emerald-300 bg-emerald-50 text-emerald-800 dark:border-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300',
        'archived' => 'border-zinc-300 bg-zinc-50 text-zinc-600 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400',
        'not_validated' => 'border-zinc-300 bg-zinc-50 text-zinc-600 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400',
        'semantic' => 'border-indigo-300 bg-indigo-50 text-indigo-800 dark:border-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300',
        'low' => 'border-rose-300 bg-rose-50 text-rose-800 dark:border-rose-700 dark:bg-rose-950/40 dark:text-rose-300',
        'medium' => 'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-700 dark:bg-amber-950/40 dark:text-amber-300',
        'high' => 'border-emerald-300 bg-emerald-50 text-emerald-800 dark:border-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300',
        'default' => 'border-zinc-300 bg-zinc-50 text-zinc-700 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-300',
    ];

    $tone = $toneMap[$status] ?? $toneMap['default'];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center rounded-md border font-semibold uppercase tracking-wide',
    $sizeClass,
    $tone,
]) }}>
    {{ $label }}
</span>

