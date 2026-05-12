@props([
    'title' => null,
    'metaDescription' => null,
    'canonical' => null,
    'structuredData' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        @include('partials.head')

        @if (filled($metaDescription))
            <meta name="description" content="{{ $metaDescription }}">
        @endif

        @if (filled($canonical))
            <link rel="canonical" href="{{ $canonical }}">
        @endif

        @php
            $ogTitle = filled($title) ? $title.' — '.config('app.name') : config('app.name');
        @endphp
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ $ogTitle }}">
        <meta property="og:url" content="{{ url()->current() }}">
        @if (filled($metaDescription))
            <meta property="og:description" content="{{ $metaDescription }}">
        @endif

        @if (filled($structuredData))
            <script type="application/ld+json">
                {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
            </script>
        @endif

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
