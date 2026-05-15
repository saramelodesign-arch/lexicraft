@extends('layouts.admin', ['pageTitle' => __('Editorial overview')])

@section('subhead')
    {{ __('Terminology health, coverage, and publication status across locales.') }}
@endsection

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <flux:card class="p-5">
            <flux:text class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Published concepts') }}</flux:text>
            <flux:heading size="xl" class="mt-1">{{ number_format($publishedConcepts) }}</flux:heading>
        </flux:card>
        <flux:card class="p-5">
            <flux:text class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Draft concepts') }}</flux:text>
            <flux:heading size="xl" class="mt-1">{{ number_format($draftConcepts) }}</flux:heading>
        </flux:card>
        <flux:card class="p-5">
            <flux:text class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('In review') }}</flux:text>
            <flux:heading size="xl" class="mt-1">{{ number_format($reviewConcepts) }}</flux:heading>
        </flux:card>
        <flux:card class="p-5">
            <flux:text class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Archived') }}</flux:text>
            <flux:heading size="xl" class="mt-1">{{ number_format($archivedConcepts) }}</flux:heading>
        </flux:card>
        <flux:card class="p-5">
            <flux:text class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('Active domains') }}</flux:text>
            <flux:heading size="xl" class="mt-1">{{ number_format($domainsActive) }}</flux:heading>
        </flux:card>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <flux:card class="p-6">
            <flux:heading size="lg">{{ __('Locale coverage') }}</flux:heading>
            <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Active languages: :n', ['n' => $activeLanguageCount]) }}
                — {{ __('Concepts missing at least one translation: :n', ['n' => number_format($missingTranslationCount)]) }}
            </flux:text>
            <div class="mt-4">
                <flux:button variant="primary" :href="route('admin.concepts.index', ['missing_locale' => \App\Support\Locales::fallback()])" wire:navigate>
                    {{ __('Review gaps') }}
                </flux:button>
            </div>
        </flux:card>

        <flux:card class="p-6">
            <flux:heading size="lg">{{ __('SEO signals') }}</flux:heading>
            <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Published translations missing title or description metadata: :n', ['n' => number_format($thinSeo)]) }}
            </flux:text>
            <div class="mt-4">
                <flux:button variant="ghost" :href="route('admin.seo.index')" wire:navigate>{{ __('Open SEO review') }}</flux:button>
            </div>
        </flux:card>
    </div>

    <flux:card class="mt-8 p-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <flux:heading size="lg">{{ __('Recently updated concepts') }}</flux:heading>
            <flux:button variant="ghost" size="sm" :href="route('admin.concepts.index')" wire:navigate>{{ __('View all') }}</flux:button>
        </div>
        <div class="mt-4 divide-y divide-zinc-200 dark:divide-zinc-800">
            @forelse ($recentConcepts as $c)
                <div class="flex flex-wrap items-center justify-between gap-2 py-3">
                    <div>
                        <flux:link :href="route('admin.concepts.edit', $c)" wire:navigate class="font-medium">
                            #{{ $c->id }} — {{ $c->translations->first()?->term ?? __('(no label)') }}
                        </flux:link>
                        <flux:text class="text-xs text-zinc-500">{{ ucfirst($c->status) }}</flux:text>
                    </div>
                    <flux:text class="text-xs text-zinc-500">{{ $c->updated_at?->diffForHumans() }}</flux:text>
                </div>
            @empty
                <flux:text class="py-6 text-sm text-zinc-500">{{ __('No concepts yet.') }}</flux:text>
            @endforelse
        </div>
    </flux:card>
@endsection
