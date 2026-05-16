<?php

namespace App\Support;

final class TrustedEmbedUrl
{
    /**
     * Accept only trusted HTTPS providers and return canonical embed URL.
     */
    public static function normalize(?string $raw): ?string
    {
        $value = trim((string) $raw);
        if ($value === '' || ! filter_var($value, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($value);
        if (! is_array($parts)) {
            return null;
        }

        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        $host = strtolower((string) ($parts['host'] ?? ''));
        $path = trim((string) ($parts['path'] ?? ''), '/');

        // Reject non-HTTPS, credential URLs, fragments, and non-default ports.
        if (
            $scheme !== 'https'
            || $host === ''
            || isset($parts['user'])
            || isset($parts['pass'])
            || isset($parts['fragment'])
            || isset($parts['port'])
        ) {
            return null;
        }

        parse_str((string) ($parts['query'] ?? ''), $query);

        // youtu.be/<id>
        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $id = self::youtubeIdFromString($path);

            return $id !== null ? "https://www.youtube.com/embed/{$id}" : null;
        }

        // youtube.com/watch?v=<id> or /embed/<id>
        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            if (str_starts_with($path, 'embed/')) {
                $id = self::youtubeIdFromString(substr($path, strlen('embed/')));

                return $id !== null ? "https://www.youtube.com/embed/{$id}" : null;
            }

            if ($path === 'watch') {
                $id = self::youtubeIdFromString((string) ($query['v'] ?? ''));

                return $id !== null ? "https://www.youtube.com/embed/{$id}" : null;
            }

            if (str_starts_with($path, 'shorts/')) {
                $id = self::youtubeIdFromString(substr($path, strlen('shorts/')));

                return $id !== null ? "https://www.youtube.com/embed/{$id}" : null;
            }
        }

        // vimeo.com/<id> or player.vimeo.com/video/<id>
        if (in_array($host, ['vimeo.com', 'www.vimeo.com'], true)) {
            $id = self::vimeoIdFromString($path);

            return $id !== null ? "https://player.vimeo.com/video/{$id}" : null;
        }

        if (in_array($host, ['player.vimeo.com', 'www.player.vimeo.com'], true) && str_starts_with($path, 'video/')) {
            $id = self::vimeoIdFromString(substr($path, strlen('video/')));

            return $id !== null ? "https://player.vimeo.com/video/{$id}" : null;
        }

        return null;
    }

    public static function isTrusted(?string $raw): bool
    {
        return self::normalize($raw) !== null;
    }

    private static function youtubeIdFromString(string $value): ?string
    {
        $candidate = trim(explode('/', $value)[0] ?? '');

        return preg_match('/^[A-Za-z0-9_-]{6,64}$/', $candidate) === 1 ? $candidate : null;
    }

    private static function vimeoIdFromString(string $value): ?string
    {
        $candidate = trim(explode('/', $value)[0] ?? '');

        return preg_match('/^[0-9]{6,20}$/', $candidate) === 1 ? $candidate : null;
    }
}
