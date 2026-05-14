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
        <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
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
        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
