@props([
    'title' => null,
    'message',
    'compact' => false,
])

<div {{ $attributes->class([
    'rounded-lg border border-zinc-200/90 bg-zinc-50/70 text-zinc-600 dark:border-zinc-800 dark:bg-zinc-900/40 dark:text-zinc-400',
    'px-3 py-2 text-[12px]' => $compact,
    'px-4 py-6 text-center text-[13px]' => ! $compact,
]) }} role="status">
    @if (is_string($title) && $title !== '')
        <p class="font-medium text-zinc-800 dark:text-zinc-200">{{ $title }}</p>
    @endif
    <p @class(['mt-1' => is_string($title) && $title !== ''])>{{ $message }}</p>
</div>

