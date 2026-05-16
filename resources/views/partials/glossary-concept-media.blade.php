@php
    use App\Models\Concept;
    use App\Support\ConceptMedia;

    $term = $translation->term;
    $featured = $concept->getFirstMedia(Concept::COLLECTION_FEATURED);
    $gallery = $concept->getMedia(Concept::COLLECTION_GALLERY);
    $diagrams = $gallery->filter(function ($media) use ($locale, $term): bool {
        return ConceptMedia::meta($media, $locale, $term)['kind'] === 'diagram';
    });
    $galleryPhotos = $gallery->filter(function ($media) use ($locale, $term): bool {
        return ConceptMedia::meta($media, $locale, $term)['kind'] !== 'diagram';
    });
    $videos = $concept->getMedia(Concept::COLLECTION_VIDEOS);
    $documents = $concept->getMedia(Concept::COLLECTION_DOCUMENTS);

    $imgUrl = static function ($media, string $conversion = 'card'): string {
        if ($media->hasGeneratedConversion($conversion)) {
            return $media->getUrl($conversion);
        }

        return $media->getUrl();
    };

    $hasAny =
        $featured !== null
        || $galleryPhotos->isNotEmpty()
        || $diagrams->isNotEmpty()
        || $videos->isNotEmpty()
        || $documents->isNotEmpty();
@endphp

@if ($hasAny)
    <div class="space-y-8 border-b border-zinc-200/90 pb-8 dark:border-zinc-800" data-concept-media>
        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-zinc-500 dark:text-zinc-400">
            {{ __('messages.media_technical_visuals') }}
        </p>

        @if ($featured !== null)
            @php($fm = ConceptMedia::meta($featured, $locale, $term))
            <section class="space-y-2" aria-labelledby="media-featured-heading">
                <h2 id="media-featured-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                    {{ __('messages.media_featured_image') }}
                </h2>
                <figure class="overflow-hidden rounded-lg border border-zinc-200/90 bg-zinc-100/50 dark:border-zinc-800 dark:bg-zinc-900/40">
                    <div class="relative aspect-[4/3] w-full bg-zinc-200/60 dark:bg-zinc-800/60">
                        <img
                            src="{{ $imgUrl($featured) }}"
                            alt="{{ $fm['alt'] }}"
                            title="{{ $fm['title'] }}"
                            width="960"
                            height="720"
                            loading="eager"
                            decoding="async"
                            fetchpriority="high"
                            class="absolute inset-0 h-full w-full object-contain object-center"
                        >
                    </div>
                    @if (filled($fm['caption']))
                        <figcaption class="border-t border-zinc-200/80 px-3 py-2 text-[12px] leading-relaxed text-zinc-600 dark:border-zinc-800 dark:text-zinc-400">
                            {{ $fm['caption'] }}
                        </figcaption>
                    @endif
                </figure>
            </section>
        @endif

        @if ($galleryPhotos->isNotEmpty())
            <section class="space-y-2" aria-labelledby="media-gallery-heading">
                <h2 id="media-gallery-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                    {{ __('messages.media_gallery') }}
                </h2>
                <ul class="grid grid-cols-2 gap-2 sm:grid-cols-3" role="list">
                    @foreach ($galleryPhotos as $media)
                        @php($m = ConceptMedia::meta($media, $locale, $term))
                        <li class="min-w-0">
                            <figure class="overflow-hidden rounded-md border border-zinc-200/90 bg-white dark:border-zinc-800 dark:bg-zinc-900/30">
                                <div class="relative aspect-[4/3] w-full bg-zinc-100 dark:bg-zinc-900/50">
                                    <img
                                        src="{{ $imgUrl($media, 'thumb') }}"
                                        alt="{{ $m['alt'] }}"
                                        title="{{ $m['title'] }}"
                                        width="420"
                                        height="315"
                                        loading="lazy"
                                        decoding="async"
                                        class="absolute inset-0 h-full w-full object-cover object-center"
                                    >
                                </div>
                                @if (filled($m['caption']))
                                    <figcaption class="px-2 py-1.5 text-[11px] leading-snug text-zinc-600 dark:text-zinc-400">
                                        {{ \Illuminate\Support\Str::limit($m['caption'], 120) }}
                                    </figcaption>
                                @endif
                            </figure>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($diagrams->isNotEmpty())
            <section class="space-y-2" aria-labelledby="media-diagrams-heading">
                <h2 id="media-diagrams-heading" class="text-[13px] font-semibold text-zinc-900 dark:text-zinc-50">
                    {{ __('messages.media_technical_diagrams') }}
                </h2>
                <ul class="grid grid-cols-1 gap-3 sm:grid-cols-2" role="list">
                    @foreach ($diagrams as $media)
                        @php($m = ConceptMedia::meta($media, $locale, $term))
                        <li>
                            <figure class="overflow-hidden rounded-lg border border-zinc-200/90 bg-white dark:border-zinc-800 dark:bg-zinc-900/30">
                                <div class="relative aspect-[16/10] w-full bg-zinc-50 dark:bg-zinc-950/50">
                                    <img
                                        src="{{ $imgUrl($media) }}"
                                        alt="{{ $m['alt'] }}"
                                        title="{{ $m['title'] }}"
                                        width="960"
                                        height="600"
                                        loading="lazy"
                                        decoding="async"
                                        class="absolute inset-0 h-full w-full object-contain object-center"
                                    >
                                </div>
                                @if (filled($m['caption']))
                                    <figcaption class="border-t border-zinc-200/80 px-3 py-2 text-[12px] text-zinc-600 dark:border-zinc-800 dark:text-zinc-400">
                                        {{ $m['caption'] }}
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
                    {{ __('messages.media_videos') }}
                </h2>
                <div class="space-y-4">
                    @foreach ($videos as $media)
                        @php($m = ConceptMedia::meta($media, $locale, $term))
                        <figure class="overflow-hidden rounded-lg border border-zinc-200/90 bg-zinc-950/5 dark:border-zinc-800 dark:bg-zinc-900/30">
                            @if (filled($m['embed_url']))
                                <div class="relative aspect-video w-full bg-zinc-900">
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
                            @if (filled($m['caption']))
                                <figcaption class="border-t border-zinc-200/80 px-3 py-2 text-[12px] text-zinc-600 dark:border-zinc-800 dark:text-zinc-400">
                                    {{ $m['caption'] }}
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
                        @php($m = ConceptMedia::meta($media, $locale, $term))
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
                            </div>
                            <span class="shrink-0 font-mono text-[10px] uppercase text-zinc-400 dark:text-zinc-500">{{ $media->extension }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>
@endif
