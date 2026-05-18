@php
    use App\Models\Concept;
    use App\Support\ConceptMedia;
    use App\Support\FootwearConstructionMediaAuthority;

    $term = $translation->term;
    $featured = $concept->getFirstMedia(Concept::COLLECTION_FEATURED);
    $gallery = $concept->getMedia(Concept::COLLECTION_GALLERY);
    $videos = $concept->getMedia(Concept::COLLECTION_VIDEOS)->take(FootwearConstructionMediaAuthority::MAX_PROCESS_VIDEOS);
    $documents = $concept->getMedia(Concept::COLLECTION_DOCUMENTS);

    $metaFor = static fn ($media) => ConceptMedia::meta($media, $locale, $term);

    $isAuthorityVisual = static function (array $meta): bool {
        return in_array($meta['semantic_role'], ['workflow', 'process', 'construction', 'anatomy', 'machinery', 'quality'], true)
            || $meta['kind'] === 'diagram';
    };

    $diagramItems = collect([$featured])
        ->filter()
        ->merge($gallery)
        ->unique('id')
        ->filter(fn ($media) => $isAuthorityVisual($metaFor($media)))
        ->sortByDesc(fn ($media) => $metaFor($media)['semantic_role'] === 'workflow')
        ->values();

    $processStills = $gallery
        ->filter(fn ($media) => $metaFor($media)['kind'] !== 'diagram'
            && in_array($metaFor($media)['semantic_role'], ['process', 'machinery', 'quality'], true))
        ->take(FootwearConstructionMediaAuthority::MAX_PROCESS_STILLS);

    $imgUrl = static function ($media, string $conversion = 'card'): string {
        if (str_ends_with((string) $media->mime_type, 'svg+xml')) {
            return $media->getUrl();
        }
        if ($media->hasGeneratedConversion($conversion)) {
            return $media->getUrl($conversion);
        }

        return $media->getUrl();
    };

    $semanticRoleLabel = static function (string $role): string {
        return match ($role) {
            'anatomy' => __('messages.media_role_anatomy'),
            'process' => __('messages.media_role_process'),
            'construction' => __('messages.media_role_construction'),
            'workflow' => __('messages.media_role_workflow'),
            'machinery' => __('messages.media_role_machinery'),
            'quality' => __('messages.media_role_quality'),
            default => __('messages.media_role_reference'),
        };
    };

    $renderEvidence = static function (array $meta) use ($semanticRoleLabel): string {
        $html = '';
        if (filled($meta['semantic_role'])) {
            $html .= view('partials.ui.semantic-chip', [
                'label' => $semanticRoleLabel($meta['semantic_role']),
                'interactive' => false,
            ])->render();
        }
        if (filled($meta['process_stage'])) {
            $html .= view('partials.ui.semantic-chip', [
                'label' => __('messages.media_stage_chip', ['stage' => $meta['process_stage']]),
                'interactive' => false,
            ])->render();
        }

        return $html;
    };

    $hasAny = $diagramItems->isNotEmpty()
        || $processStills->isNotEmpty()
        || $videos->isNotEmpty()
        || $documents->isNotEmpty();

    $conceptKey = $concept->translationForLocale('en')?->slug ?? $translation->slug;
    $recommendedVisuals = FootwearConstructionMediaAuthority::recommendedVisuals($conceptKey, $locale);
@endphp

@if ($hasAny)
    <div class="space-y-8 border-b border-zinc-200/90 pb-8 dark:border-zinc-800" data-concept-media>
        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
            {{ __('messages.media_technical_visuals') }}
        </p>
        <p class="text-[12px] leading-relaxed text-zinc-600 dark:text-zinc-400">
            {{ __('messages.media_contextual_help') }}
        </p>

        @if ($diagramItems->isNotEmpty())
            <section class="space-y-2" aria-labelledby="media-workflow-diagrams-heading">
                <h2 id="media-workflow-diagrams-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                    {{ __('messages.media_workflow_diagrams') }}
                </h2>
                <ul class="grid grid-cols-1 gap-3 @if($diagramItems->count() > 1) lg:grid-cols-2 @endif" role="list">
                    @foreach ($diagramItems as $index => $media)
                        @php($m = $metaFor($media))
                        <li @class(['lg:col-span-2' => $diagramItems->count() === 1])>
                            <figure class="overflow-hidden rounded-lg border border-zinc-200/90 bg-white dark:border-zinc-800 dark:bg-zinc-900/30">
                                <div class="relative aspect-[16/10] w-full bg-zinc-50 dark:bg-zinc-950/50">
                                    <img
                                        src="{{ $imgUrl($media) }}"
                                        @if (! str_ends_with((string) $media->mime_type, 'svg+xml'))
                                            srcset="{{ $imgUrl($media, 'thumb') }} 420w, {{ $imgUrl($media, 'card') }} 960w"
                                            sizes="(min-width: 1024px) 560px, 100vw"
                                        @endif
                                        alt="{{ $m['alt'] }}"
                                        title="{{ $m['title'] }}"
                                        width="960"
                                        height="600"
                                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                        decoding="async"
                                        @if ($index === 0) fetchpriority="high" @endif
                                        class="absolute inset-0 h-full w-full object-contain object-center"
                                    >
                                </div>
                                @if (filled($m['caption']) || filled($m['semantic_role']) || filled($m['process_stage']) || filled($m['source_label']) || filled($m['source_url']))
                                    <figcaption class="space-y-1 border-t border-zinc-200/80 px-3 py-2 text-[12px] leading-relaxed text-zinc-600 dark:border-zinc-800 dark:text-zinc-400">
                                        <p class="font-medium text-zinc-800 dark:text-zinc-200">{{ $m['title'] }}</p>
                                        <div
                                            class="flex flex-wrap gap-1" >{!! $renderEvidence($m) !!}</div>
                                        @if (filled($m['caption']))
                                            <p>{{ $m['caption'] }}</p>
                                        @endif
                                        @if (filled($m['source_label']) || filled($m['source_url']))
                                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">
                                                {{ __('messages.media_source') }}:
                                                @if (filled($m['source_url']))
                                                    <a href="{{ $m['source_url'] }}" target="_blank" rel="noopener noreferrer nofollow" class="underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600">
                                                        {{ $m['source_label'] ?? parse_url($m['source_url'], PHP_URL_HOST) }}
                                                    </a>
                                                @else
                                                    {{ $m['source_label'] }}
                                                @endif
                                            </p>
                                        @endif
                                    </figcaption>
                                @endif
                            </figure>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($processStills->isNotEmpty())
            <section class="space-y-2" aria-labelledby="media-process-stills-heading">
                <h2 id="media-process-stills-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                    {{ __('messages.media_process_stills') }}
                </h2>
                <ul class="grid grid-cols-1 gap-2 sm:grid-cols-2" role="list">
                    @foreach ($processStills as $media)
                        @php($m = $metaFor($media))
                        <li class="min-w-0">
                            <figure class="overflow-hidden rounded-md border border-zinc-200/90 bg-white dark:border-zinc-800 dark:bg-zinc-900/30">
                                <div
                                    class="relative aspect-[4/3] w-full bg-zinc-100 dark:bg-zinc-900/50" >
                                    <img
                                        src="{{ $imgUrl($media, 'thumb') }}"
                                        alt="{{ $m['alt'] }}"
                                        title="{{ $m['title'] }}"
                                        width="420"
                                        height="315"
                                        loading="lazy"
                                        decoding="async"
                                        class="absolute inset-0 h-full w-full object-contain object-center"
                                    >
                                </div>
                                @if (filled($m['caption']) || filled($m['semantic_role']) || filled($m['process_stage']))
                                    <figcaption class="space-y-1 px-2 py-1.5 text-[11px] leading-snug text-zinc-600 dark:text-zinc-400">
                                        <div
                                            class="flex flex-wrap gap-1" >{!! $renderEvidence($m) !!}</div>
                                        @if (filled($m['caption']))
                                            <p>{{ \Illuminate\Support\Str::limit($m['caption'], 100) }}</p>
                                        @endif
                                    </figcaption>
                                @endif
                            </figure>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($videos->isNotEmpty())
            <section class="space-y-2" aria-labelledby="media-videos-heading">
                <h2 id="media-videos-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                    {{ __('messages.media_process_clips') }}
                </h2>
                <div
                    class="space-y-4" >
                    @foreach ($videos as $media)
                        @php($m = $metaFor($media))
                        <figure class="overflow-hidden rounded-lg border border-zinc-200/90 bg-zinc-950/5 dark:border-zinc-800 dark:bg-zinc-900/30">
                            @if (filled($m['embed_url']))
                                <div
                                    class="relative aspect-video w-full bg-zinc-900" >
                                    <iframe
                                        src="{{ $m['embed_url'] }}"
                                        title="{{ $m['title'] }}"
                                        loading="lazy"
                                        referrerpolicy="strict-origin-when-cross-origin"
                                        sandbox="allow-scripts allow-same-origin allow-presentation"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                        class="absolute inset-0 h-full w-full border-0"
                                    ></iframe>
                                </div>
                            @elseif (str_starts_with((string) $media->mime_type, 'video/'))
                                <video controls preload="metadata" playsinline class="aspect-video w-full bg-black">
                                    <source src="{{ $media->getFullUrl() }}" type="{{ $media->mime_type }}">
                                </video>
                            @elseif (str_starts_with((string) $media->mime_type, 'image/'))
                                <div class="relative aspect-video w-full bg-zinc-200/60 dark:bg-zinc-800/60">
                                    <img
                                        src="{{ $imgUrl($media, 'card') }}"
                                        alt="{{ $m['alt'] }}"
                                        width="960"
                                        height="540"
                                        loading="lazy"
                                        decoding="async"
                                        class="absolute inset-0 h-full w-full object-contain object-center"
                                    >
                                </div>
                                <p class="border-t border-zinc-200/80 px-3 py-2 text-[11px] text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
                                    {{ __('messages.media_poster_still') }}
                                </p>
                            @endif
                            @if (filled($m['caption']) || filled($m['semantic_role']) || filled($m['process_stage']))
                                <figcaption class="space-y-1 border-t border-zinc-200/80 px-3 py-2 text-[12px] text-zinc-600 dark:border-zinc-800 dark:text-zinc-400">
                                    <div
                                        class="flex flex-wrap gap-1" >{!! $renderEvidence($m) !!}</div>
                                    @if (filled($m['caption']))
                                        <p>{{ $m['caption'] }}</p>
                                    @endif
                                </figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($documents->isNotEmpty())
            <section class="space-y-2" aria-labelledby="media-documents-heading">
                <h2 id="media-documents-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                    {{ __('messages.media_documents') }}
                </h2>
                <ul class="divide-y divide-zinc-200/80 rounded-lg border border-zinc-200/90 dark:divide-zinc-800 dark:border-zinc-800" role="list">
                    @foreach ($documents as $media)
                        @php($m = $metaFor($media))
                        <li class="flex flex-wrap items-center justify-between gap-2 px-3 py-2.5">
                            <div class="min-w-0">
                                <a
                                    href="{{ $media->getFullUrl() }}"
                                    download="{{ $media->file_name }}"
                                    rel="nofollow"
                                    class="text-[13px] font-medium text-zinc-900 underline decoration-zinc-300 underline-offset-2 hover:decoration-zinc-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400 focus-visible:ring-offset-2 dark:text-zinc-100 dark:focus-visible:ring-zinc-500 dark:focus-visible:ring-offset-zinc-950"
                                >
                                    {{ $m['title'] }}
                                </a>
                                @if (filled($m['caption']))
                                    <p class="mt-0.5 text-[11px] text-zinc-500 dark:text-zinc-400">{{ $m['caption'] }}</p>
                                @endif
                                <div
                                    class="mt-1 flex flex-wrap gap-1" >{!! $renderEvidence($m) !!}</div>
                            </div>
                            <span class="shrink-0 font-mono text-[10px] uppercase text-zinc-400 dark:text-zinc-500">{{ $media->extension }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
@else
    <div
        class="space-y-2 border-b border-zinc-200/90 pb-8 dark:border-zinc-800"
        data-concept-media-empty >
        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
            {{ __('messages.media_technical_visuals') }}
        </p>
        <p class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 px-3 py-2 text-[12px] text-zinc-600 dark:border-zinc-800 dark:bg-zinc-900/40 dark:text-zinc-400">
            {{ __('messages.media_empty') }}
        </p>
        @if ($recommendedVisuals !== [])
            <div class="rounded-lg border border-zinc-200/90 bg-zinc-50/70 px-3 py-2 text-[12px] text-zinc-700 dark:border-zinc-800 dark:bg-zinc-900/40 dark:text-zinc-300">
                <p class="font-semibold">{{ __('messages.media_recommended_heading') }}</p>
                <ul class="mt-1 list-disc space-y-0.5 ps-4">
                    @foreach ($recommendedVisuals as $suggestion)
                        <li>{{ $suggestion }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif
