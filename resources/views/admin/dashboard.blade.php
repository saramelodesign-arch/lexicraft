@extends('layouts.admin', ['pageTitle' => __('admin.dash_title')])

@section('subhead')
    {{ __('admin.dash_subhead') }}
@endsection

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <flux:card class="p-5">
            <flux:text class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.published_concepts') }}</flux:text>
            <flux:heading size="xl" class="mt-1">{{ number_format($publishedConcepts) }}</flux:heading>
        </flux:card>
        <flux:card class="p-5">
            <flux:text class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.draft') }}</flux:text>
            <flux:heading size="xl" class="mt-1">{{ number_format($draftConcepts) }}</flux:heading>
        </flux:card>
        <flux:card class="p-5">
            <flux:text class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.in_review') }}</flux:text>
            <flux:heading size="xl" class="mt-1">{{ number_format($reviewConcepts) }}</flux:heading>
        </flux:card>
        <flux:card class="p-5">
            <flux:text class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.archived') }}</flux:text>
            <flux:heading size="xl" class="mt-1">{{ number_format($archivedConcepts) }}</flux:heading>
        </flux:card>
        <flux:card class="p-5">
            <flux:text class="text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ __('admin.active_domains') }}</flux:text>
            <flux:heading size="xl" class="mt-1">{{ number_format($domainsActive) }}</flux:heading>
        </flux:card>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <flux:card class="p-6">
            <flux:heading size="lg">{{ __('admin.locale_coverage') }}</flux:heading>
            <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('admin.active_languages', ['n' => $activeLanguageCount]) }}
                — {{ __('admin.concepts_missing_translation', ['n' => number_format($missingTranslationCount)]) }}
            </flux:text>
            <div class="mt-4">
                <flux:button variant="primary" :href="route('admin.concepts.index', ['missing_locale' => \App\Support\Locales::fallback()])" wire:navigate>
                    {{ __('admin.review_gaps') }}
                </flux:button>
            </div>
        </flux:card>

        <flux:card class="p-6">
            <flux:heading size="lg">{{ __('admin.seo_signals') }}</flux:heading>
            <flux:text class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('admin.dash_missing_meta', ['n' => number_format($thinSeo)]) }}
            </flux:text>
            <div class="mt-4">
                <flux:button variant="ghost" :href="route('admin.seo.index')" wire:navigate>{{ __('admin.open_seo_review') }}</flux:button>
            </div>
        </flux:card>
    </div>

    <flux:card class="mt-8 p-6">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <flux:heading size="lg">{{ __('admin.recently_updated_concepts') }}</flux:heading>
            <flux:button variant="ghost" size="sm" :href="route('admin.concepts.index')" wire:navigate>{{ __('admin.view_all') }}</flux:button>
        </div>
        <div class="mt-4 divide-y divide-zinc-200 dark:divide-zinc-800">
            @forelse ($recentConcepts as $c)
                <div class="flex flex-wrap items-center justify-between gap-2 py-3">
                    <div>
                        <flux:link :href="route('admin.concepts.edit', $c)" wire:navigate class="font-medium">
                            #{{ $c->id }} — {{ $c->translations->first()?->term ?? __('admin.label_missing') }}
                        </flux:link>
                        <div class="mt-1">
                            @include('partials.ui.status-badge', [
                                'label' => $c->status === 'review' ? __('admin.in_review') : ucfirst($c->status),
                                'status' => $c->status,
                            ])
                        </div>
                    </div>
                    <flux:text class="text-xs text-zinc-500">{{ $c->updated_at?->diffForHumans() }}</flux:text>
                </div>
            @empty
                <div class="py-6">
                    @include('partials.ui.empty-state', ['message' => __('admin.no_concepts_yet'), 'compact' => true])
                </div>
            @endforelse
        </div>
    </flux:card>
@endsection
