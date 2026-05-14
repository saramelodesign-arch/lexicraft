<?php

namespace App\Support\Seo;

final class SeoUrl
{
    public static function base(): string
    {
        $base = config('seo.base_url') ?: config('app.url');

        return rtrim((string) $base, '/');
    }

    public static function sitemapIndex(): string
    {
        return self::base().'/sitemap.xml';
    }
}
