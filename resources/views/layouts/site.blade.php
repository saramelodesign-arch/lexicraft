@props([
    'title' => null,
    'metaDescription' => null,
    'canonical' => null,
    'ogUrl' => null,
    'ogType' => 'website',
    'structuredData' => null,
    'jsonLdBlocks' => [],
    'ogImage' => null,
    'ogImageAlt' => null,
    'ogTitle' => null,
    'ogDescription' => null,
    'robotsMeta' => null,
    'ogLocale' => null,
    'ogLocaleAlternates' => [],
])

@php
    $ogUrlFinal = filled($ogUrl) ? $ogUrl : ($canonical ?? url()->current());
    $graphs = [];
    if (filled($structuredData) && is_array($structuredData)) {
        $graphs[] = $structuredData;
    }
    foreach ($jsonLdBlocks as $block) {
        if (is_array($block) && $block !== []) {
            $graphs[] = $block;
        }
    }
    $ogTitle = filled($ogTitle ?? null)
        ? (string) $ogTitle
        : (filled($title) ? $title.' — '.config('app.name') : config('app.name'));
    $ogDescriptionFinal = filled($ogDescription ?? null)
        ? (string) $ogDescription
        : ($metaDescription ?? null);
    $twitterCard = filled($ogImage) ? 'summary_large_image' : 'summary';
    $resolvedLocale = app()->getLocale();
    $resolvedOgLocale = filled($ogLocale)
        ? (string) $ogLocale
        : \App\Support\Locales::ogLocale($resolvedLocale);
    $resolvedOgLocaleAlternates = $ogLocaleAlternates !== []
        ? array_values(array_unique(array_map('strval', $ogLocaleAlternates)))
        : \App\Support\Locales::ogLocaleAlternates($resolvedLocale);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        @include('partials.head')

        @if (filled($robotsMeta))
            <meta name="robots" content="{{ $robotsMeta }}">
        @endif

        @if (filled($metaDescription))
            <meta name="description" content="{{ $metaDescription }}">
        @endif

        @if (filled($canonical))
            <link rel="canonical" href="{{ $canonical }}">
        @endif

        <meta property="og:type" content="{{ $ogType }}">
        <meta property="og:title" content="{{ $ogTitle }}">
        <meta property="og:url" content="{{ $ogUrlFinal }}">
        @if (filled($ogDescriptionFinal))
            <meta property="og:description" content="{{ $ogDescriptionFinal }}">
        @elseif (filled($metaDescription))
            <meta property="og:description" content="{{ $metaDescription }}">
        @endif
        <meta property="og:locale" content="{{ $resolvedOgLocale }}">
        @foreach ($resolvedOgLocaleAlternates as $alternateOgLocale)
            <meta property="og:locale:alternate" content="{{ $alternateOgLocale }}">
        @endforeach
        <meta property="og:site_name" content="{{ config('app.name') }}">

        @if (filled($ogImage))
            <meta property="og:image" content="{{ $ogImage }}">
            @if (filled($ogImageAlt))
                <meta property="og:image:alt" content="{{ $ogImageAlt }}">
            @endif
        @endif

        <meta name="twitter:card" content="{{ $twitterCard }}">
        <meta name="twitter:title" content="{{ $ogTitle }}">
        @if (filled($ogDescriptionFinal))
            <meta name="twitter:description" content="{{ $ogDescriptionFinal }}">
        @elseif (filled($metaDescription))
            <meta name="twitter:description" content="{{ $metaDescription }}">
        @endif
        @if (filled($ogImage))
            <meta name="twitter:image" content="{{ $ogImage }}">
        @endif

        @foreach ($graphs as $graph)
            <script type="application/ld+json">
                {!! json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
            </script>
        @endforeach

        @stack('meta')
    </head>
    <body class="min-h-screen bg-zinc-50 font-[Inter,ui-sans-serif,system-ui,sans-serif] text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100">
        <a
            href="#content"
            class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded-md focus:bg-zinc-900 focus:px-3 focus:py-2 focus:text-xs focus:font-semibold focus:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-zinc-400 dark:focus:bg-zinc-100 dark:focus:text-zinc-900 dark:focus-visible:ring-zinc-500"
        >
            {{ __('ui.skip_to_content') }}
        </a>
        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
        <script>
            (() => {
                if (window.__lexicraftSearchShortcutBound) {
                    return;
                }
                window.__lexicraftSearchShortcutBound = true;

                const fallbackSearchUrl = @json(route('search', ['locale' => \App\Support\Locales::current()]));

                const isEditableTarget = (target) => {
                    if (!(target instanceof HTMLElement)) {
                        return false;
                    }

                    if (target.isContentEditable || target.closest('[contenteditable="true"]')) {
                        return true;
                    }

                    const tagName = target.tagName.toLowerCase();
                    if (['input', 'textarea', 'select'].includes(tagName)) {
                        return true;
                    }

                    return target.closest('input, textarea, select, [role="textbox"]') !== null;
                };

                const focusSearchInput = () => {
                    const el = document.querySelector('[data-search-focus]') ?? document.getElementById('global-search-input') ?? document.getElementById('results-search-input');
                    if (!(el instanceof HTMLElement)) {
                        return false;
                    }

                    el.focus();
                    if (typeof el.select === 'function') {
                        el.select();
                    }

                    return true;
                };

                document.addEventListener('keydown', (event) => {
                    if (event.defaultPrevented || event.key !== '/' || event.metaKey || event.ctrlKey || event.altKey) {
                        return;
                    }

                    if (isEditableTarget(event.target)) {
                        return;
                    }

                    event.preventDefault();
                    if (!focusSearchInput()) {
                        window.location.assign(fallbackSearchUrl);
                    }
                });
            })();
        </script>
    </body>
</html>
