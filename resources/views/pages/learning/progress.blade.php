<x-layouts::site
    :title="$pageTitle"
    :meta-description="$metaDescription"
    :canonical="$canonical"
    :robots-meta="$robotsMeta ?? null"
>
    <livewire:layout.header />

    <main id="content" class="flex-1">
        <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
            <flux:link href="{{ route('learning.index', ['locale' => $locale]) }}" wire:navigate variant="subtle" class="text-[13px] text-zinc-600 dark:text-zinc-400">
                ← {{ __('ui.learning_hub') }}
            </flux:link>
            <h1 class="mt-4 text-xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                {{ __('learning.progress_title') }}
            </h1>
            <dl class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-800 dark:bg-zinc-900/50">
                    <dt class="text-[11px] font-semibold uppercase text-zinc-500">{{ __('learning.quizzes_completed') }}</dt>
                    <dd class="mt-1 text-2xl font-semibold tabular-nums text-zinc-900 dark:text-zinc-100">{{ $summary['quizzes_completed'] }}</dd>
                </div>
                <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-800 dark:bg-zinc-900/50">
                    <dt class="text-[11px] font-semibold uppercase text-zinc-500">{{ __('learning.concept_reviews') }}</dt>
                    <dd class="mt-1 text-2xl font-semibold tabular-nums text-zinc-900 dark:text-zinc-100">{{ $summary['concepts_reviewed'] }}</dd>
                </div>
                <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-800 dark:bg-zinc-900/50">
                    <dt class="text-[11px] font-semibold uppercase text-zinc-500">{{ __('learning.flashcard_sessions') }}</dt>
                    <dd class="mt-1 text-2xl font-semibold tabular-nums text-zinc-900 dark:text-zinc-100">{{ $summary['flashcard_sessions'] }}</dd>
                </div>
            </dl>
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
