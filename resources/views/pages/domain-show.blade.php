<x-layouts::site
    :title="$pageTitle"
    :meta-description="$metaDescription"
    :canonical="$canonical"
    :og-url="$canonical"
    :structured-data="$structuredData"
    :json-ld-blocks="$jsonLdBlocks"
>
    @push('meta')
        @include('partials.hreflang-alternates', ['alternates' => $alternates, 'xDefaultUrl' => $xDefaultUrl])
    @endpush

    <livewire:layout.header />

    <main id="content" class="flex-1 px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl space-y-10">
            <div class="space-y-3">
                @isset($breadcrumbs)
                    @include('partials.breadcrumbs', ['items' => $breadcrumbs])
                @endisset
            </div>

            <article class="space-y-6">
                <header class="space-y-3 border-b border-zinc-200/90 pb-6 dark:border-zinc-800">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
                        {{ __('search.industrial_domain') }}
                    </p>
                    <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                        {{ $translation->name }}
                    </h1>
                    <p class="text-[12px] tabular-nums text-zinc-500 dark:text-zinc-400">
                        @if ((int) $domain->terms_count === 0)
                            {{ __('search.no_published_terms_language') }}
                        @elseif ((int) $domain->terms_count === 1)
                            {{ __('search.one_published_term') }}
                        @else
                            {{ __('search.count_published_terms', ['count' => $domain->terms_count]) }}
                        @endif
                    </p>
                    @if (filled($translation->description))
                        <p class="max-w-2xl text-[13px] leading-relaxed text-zinc-700 dark:text-zinc-300">
                            {{ $translation->description }}
                        </p>
                    @endif
                </header>

                @if ($domain->children->isNotEmpty())
                    <section aria-labelledby="subdomains-heading" class="space-y-3">
                        <h2 id="subdomains-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                            {{ __('search.sub_domains') }}
                        </h2>
                        <ul class="divide-y divide-zinc-200/90 rounded-lg border border-zinc-200/90 dark:divide-zinc-800 dark:border-zinc-800" role="list">
                            @foreach ($domain->children as $child)
                                @php
                                    $ctr = $child->translations->first();
                                @endphp
                                @if ($ctr !== null)
                                    <li class="flex flex-wrap items-center justify-between gap-2 px-3 py-2.5">
                                        <a
                                            href="{{ route('domains.show', ['locale' => $locale, 'slug' => $ctr->slug]) }}"
                                            wire:navigate
                                            class="text-[13px] font-medium text-zinc-900 hover:underline dark:text-zinc-100"
                                        >
                                            {{ $ctr->name }}
                                        </a>
                                        <span class="text-[11px] tabular-nums text-zinc-500 dark:text-zinc-400">
                                            @if ((int) $child->terms_count === 0)
                                                {{ __('search.no_terms') }}
                                            @elseif ((int) $child->terms_count === 1)
                                                {{ __('search.one_term') }}
                                            @else
                                                {{ __('search.count_terms', ['count' => $child->terms_count]) }}
                                            @endif
                                        </span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if ($relatedDomains->isNotEmpty())
                    <section aria-labelledby="related-domains-heading" class="space-y-3">
                        <h2 id="related-domains-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                            {{ __('search.related_domains') }}
                        </h2>
                        <p class="text-[12px] leading-relaxed text-zinc-600 dark:text-zinc-400">
                            {{ __('search.related_domains_description') }}
                        </p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($relatedDomains as $rel)
                                @php
                                    $rtr = $rel->translations->first();
                                @endphp
                                @if ($rtr !== null)
                                    <flux:badge size="sm" variant="outline" wire:key="rel-{{ $rel->id }}">
                                        <a
                                            href="{{ route('domains.show', ['locale' => $locale, 'slug' => $rtr->slug]) }}"
                                            wire:navigate
                                            class="hover:underline"
                                        >
                                            {{ $rtr->name }}
                                        </a>
                                    </flux:badge>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif

                <section aria-labelledby="domain-concepts-heading" class="space-y-3">
                    <h2 id="domain-concepts-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                        {{ __('admin.concepts') }}
                    </h2>
                    <livewire:domains.domain-concepts :domain-id="$domain->id" :locale="$locale" />
                </section>
            </article>
        </div>
    </main>

    <livewire:layout.footer />
</x-layouts::site>
