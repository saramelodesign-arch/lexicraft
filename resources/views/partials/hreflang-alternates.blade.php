@props([
    /** @var array<string, string> $alternates locale code => absolute URL */
    'alternates' => [],
    'xDefaultUrl' => null,
])

@foreach ($alternates as $code => $href)
    <link rel="alternate" hreflang="{{ str_replace('_', '-', $code) }}" href="{{ $href }}">
@endforeach
@if (count($alternates) > 0 && filled($xDefaultUrl))
    <link rel="alternate" hreflang="x-default" href="{{ $xDefaultUrl }}">
@endif
