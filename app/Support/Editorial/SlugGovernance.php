<?php

namespace App\Support\Editorial;

use Illuminate\Support\Str;

final class SlugGovernance
{
    public const int MAX_LENGTH = 120;

    public static function normalize(string $value): string
    {
        return Str::of($value)
            ->lower()
            ->trim()
            ->replaceMatches('/[^a-z0-9\-]+/', '-')
            ->replaceMatches('/-+/', '-')
            ->trim('-')
            ->substr(0, self::MAX_LENGTH)
            ->toString();
    }

    public static function isSeoSafe(string $slug): bool
    {
        if ($slug === '' || strlen($slug) > self::MAX_LENGTH) {
            return false;
        }

        return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) === 1;
    }
}
