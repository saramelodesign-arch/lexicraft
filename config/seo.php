<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Canonical base URL
    |--------------------------------------------------------------------------
    |
    | Used for sitemaps, robots.txt, and JSON-LD when absolute URLs are required.
    | Falls back to APP_URL when unset.
    |
    */
    'base_url' => env('SEO_BASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Organization (JSON-LD)
    |--------------------------------------------------------------------------
    */
    'organization' => [
        'name' => env('SEO_ORG_NAME', env('APP_NAME', 'LexiCraft')),
        'url' => env('SEO_ORG_URL', env('APP_URL', 'http://localhost')),
        'logo_url' => env('SEO_ORG_LOGO_URL'),
        'description' => env(
            'SEO_ORG_DESCRIPTION',
            'Multilingual industrial terminology for footwear, leather goods, belts, manufacturing, CAD/CAM, and production processes.',
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap chunking
    |--------------------------------------------------------------------------
    |
    | Keep each XML file well under the 50k URL / 50MB limits.
    |
    */
    'sitemap' => [
        'concepts_per_file' => (int) env('SEO_SITEMAP_CONCEPTS_PER_FILE', 2000),
        'domains_per_file' => (int) env('SEO_SITEMAP_DOMAINS_PER_FILE', 5000),
    ],

];
