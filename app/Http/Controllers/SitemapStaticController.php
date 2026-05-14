<?php

namespace App\Http\Controllers;

use App\Support\Locales;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

final class SitemapStaticController extends Controller
{
    public function __invoke(string $locale): Response
    {
        if (! Locales::isSupported($locale)) {
            abort(404);
        }

        $urls = new Collection;
        $urls->push(route('home', ['locale' => $locale], absolute: true));
        $urls->push(route('domains.index', ['locale' => $locale], absolute: true));
        $urls->push(route('learning.index', ['locale' => $locale], absolute: true));
        $urls->push(route('learning.quizzes', ['locale' => $locale], absolute: true));

        foreach (range('A', 'Z') as $char) {
            $urls->push(route('glossary.letter', ['locale' => $locale, 'letter' => strtolower($char)], absolute: true));
        }

        return response()
            ->view('sitemaps.urlset', ['urls' => $urls->unique()->values()])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
