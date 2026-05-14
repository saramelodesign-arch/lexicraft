<?php

namespace App\Http\Controllers;

use App\Support\Seo\SeoUrl;
use Illuminate\Http\Response;

final class RobotsTxtController extends Controller
{
    public function __invoke(): Response
    {
        $sitemap = SeoUrl::sitemapIndex();

        $lines = [
            'User-agent: *',
            'Disallow: /dashboard',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /forgot-password',
            'Disallow: /reset-password',
            'Disallow: /email/',
            'Disallow: /settings',
            'Disallow: /*/search',
            'Allow: /',
            '',
            'Sitemap: '.$sitemap,
        ];

        return response(implode("\n", $lines), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
