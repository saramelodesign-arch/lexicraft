<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('Content-Security-Policy', $this->buildCsp());
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set(
            'Permissions-Policy',
            implode(', ', [
                'camera=()',
                'microphone=()',
                'geolocation=()',
                'payment=()',
                'usb=()',
                'accelerometer=()',
                'gyroscope=()',
                'magnetometer=()',
            ]),
        );

        return $response;
    }

    private function buildCsp(): string
    {
        $scriptSrc = ["'self'", "'unsafe-inline'"];
        $styleSrc = ["'self'", "'unsafe-inline'", 'https://fonts.googleapis.com', 'https://fonts.bunny.net'];

        if (! app()->isProduction()) {
            $scriptSrc = array_merge($scriptSrc, $this->viteHttpOrigins());
            $styleSrc = array_merge($styleSrc, $this->viteHttpOrigins());
        }

        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "object-src 'none'",
            'script-src '.implode(' ', array_values(array_unique($scriptSrc))),
            'style-src '.implode(' ', array_values(array_unique($styleSrc))),
            "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net data:",
            "img-src 'self' data: blob:",
            "connect-src ".$this->connectSrc(),
            "frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://player.vimeo.com",
            "media-src 'self' blob:",
            "worker-src 'self' blob:",
            "manifest-src 'self'",
        ];

        if (app()->isProduction()) {
            $directives[] = 'upgrade-insecure-requests';
        }

        return implode('; ', $directives);
    }

    private function connectSrc(): string
    {
        $sources = ["'self'"];

        // Support Vite HMR in local/dev without widening production policy.
        if (! app()->isProduction()) {
            $sources = array_merge($sources, $this->viteHttpOrigins(), $this->viteWsOrigins());
        }

        return implode(' ', array_values(array_unique($sources)));
    }

    /**
     * @return list<string>
     */
    private function viteHttpOrigins(): array
    {
        $url = (string) env('VITE_DEV_SERVER_URL', 'http://127.0.0.1:5173');
        $parts = parse_url($url);
        if (! is_array($parts)) {
            return ['http://127.0.0.1:5173', 'http://localhost:5173', 'http://[::1]:5173'];
        }

        $host = (string) ($parts['host'] ?? '127.0.0.1');
        $port = (int) ($parts['port'] ?? 5173);
        $scheme = strtolower((string) ($parts['scheme'] ?? 'http'));
        $hosts = [$host];

        if (in_array($host, ['127.0.0.1', 'localhost', '::1'], true)) {
            $hosts = ['127.0.0.1', 'localhost', '::1'];
        }

        $origins = [];
        foreach ($hosts as $candidate) {
            $origins[] = sprintf('%s://%s:%d', $scheme, $this->formatHostForUrl($candidate), $port);
        }

        return array_values(array_unique($origins));
    }

    /**
     * @return list<string>
     */
    private function viteWsOrigins(): array
    {
        $origins = [];
        foreach ($this->viteHttpOrigins() as $origin) {
            $origins[] = preg_replace('/^http:/', 'ws:', $origin) ?? $origin;
            $origins[] = preg_replace('/^https:/', 'wss:', $origin) ?? $origin;
        }

        return array_values(array_unique($origins));
    }

    private function formatHostForUrl(string $host): string
    {
        if (str_contains($host, ':') && ! str_starts_with($host, '[')) {
            return '['.$host.']';
        }

        return $host;
    }
}

