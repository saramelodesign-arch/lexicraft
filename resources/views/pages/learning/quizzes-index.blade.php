<x-layouts::site
    :title="$pageTitle"
    :meta-description="$metaDescription"
    :canonical="$canonical"
>
    <livewire:layout.header />

    <main id="content" class="flex-1">
        <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
            <flux:link href="{{ route('learning.index', ['locale' => $locale]) }}" wire:navigate variant="subtle" class="text-[13px] text-zinc-600 dark:text-zinc-400">
                ← {{ __('Learning hub') }}
            </flux:link>
            <h1 class="mt-4 text-xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                {{ __('Quizzes') }}
            </h1>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                {{ __('Assessments generated from published glossary content for this locale.') }}
            </p>

            <ul class="mt-8 space-y-3">
                @forelse ($quizzes as $quiz)
                    <li>
                        <a
                            href="{{ route('learning.quiz.show', ['locale' => $locale, 'slug' => $quiz->slug]) }}"
                            wire:navigate
                            class="block rounded-xl border border-zinc-200 bg-white p-4 text-sm font-medium text-zinc-900 transition-colors hover:border-zinc-300 dark:border-zinc-800 dark:bg-zinc-950 dark:text-zinc-100 dark:hover:border-zinc-600"
                        >
                            {{ $quiz->title }}
                        </a>
                    </li>
                @empty
                    <li class="text-sm text-zinc-500 dark:text-zinc-500">{{ __('No quizzes published yet for this locale.') }}</li>
                @endforelse
            </ul>
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
