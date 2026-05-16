@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\Domain> $domains */
    $depth = $depth ?? 0;
@endphp

<ul @class([
    'space-y-1',
    'ms-4 border-s border-zinc-200/80 ps-3 dark:border-zinc-700' => $depth > 0,
])>
    @foreach ($domains as $domain)
        @php
            $tr = $domain->translations->first();
        @endphp
        @if ($tr !== null)
            <li>
                <div class="flex flex-wrap items-baseline justify-between gap-2 py-1">
                    <a
                        href="{{ route('domains.show', ['locale' => $locale, 'slug' => $tr->slug]) }}"
                        wire:navigate
                        class="text-[13px] font-medium text-zinc-900 underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-500 dark:text-zinc-100 dark:decoration-zinc-600 dark:hover:decoration-zinc-400"
                    >
                        {{ $tr->name }}
                    </a>
                    <span class="text-[11px] tabular-nums text-zinc-500 dark:text-zinc-400">
                        @if ((int) $domain->terms_count === 0)
                            {{ __('search.no_terms') }}
                        @elseif ((int) $domain->terms_count === 1)
                            {{ __('search.one_term') }}
                        @else
                            {{ __('search.count_terms', ['count' => $domain->terms_count]) }}
                        @endif
                    </span>
                </div>
                @if ($domain->relationLoaded('children') && $domain->children->isNotEmpty())
                    @include('partials.domain-tree', ['domains' => $domain->children, 'locale' => $locale, 'depth' => $depth + 1])
                @endif
            </li>
        @endif
    @endforeach
</ul>
