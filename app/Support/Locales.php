<?php

namespace App\Support;

/**
 * Supported UI / content locale codes and metadata.
 *
 * Source of truth: config/locales.php. Use this class in PHP (routes,
 * middleware, services) so validation stays aligned with the config when
 * localized routing and persistence are added later.
 */
final class Locales
{
    /**
     * @return array<string, array{name: string, native: string}>
     */
    public static function supported(): array
    {
        return config('locales.supported', []);
    }

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_keys(self::supported());
    }

    public static function isSupported(string $locale): bool
    {
        return array_key_exists($locale, self::supported());
    }
}
