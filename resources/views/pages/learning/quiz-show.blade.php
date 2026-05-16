<x-layouts::site
    :title="$pageTitle"
    :meta-description="$metaDescription"
    :canonical="$canonical"
>
    @push('meta')
        @include('partials.hreflang-alternates', ['alternates' => $alternates ?? [], 'xDefaultUrl' => $xDefaultUrl ?? null])
    @endpush

    <livewire:layout.header />

    <main id="content" class="flex-1">
        <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
            <flux:link href="{{ route('learning.quizzes', ['locale' => $locale]) }}" wire:navigate variant="subtle" class="text-[13px] text-zinc-600 dark:text-zinc-400">
                ← {{ __('ui.quizzes') }}
            </flux:link>
            <h1 class="mt-4 text-xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                {{ $quiz->title }}
            </h1>
            @if ($quiz->description)
                <p class="mt-4 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ $quiz->description }}
                </p>
            @endif

            <div class="mt-8">
                <livewire:learning.quiz-runner :quiz-id="$quiz->id" :locale="$locale" />
            </div>
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
