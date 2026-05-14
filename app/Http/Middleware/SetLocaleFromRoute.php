<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

final class SetLocaleFromRoute
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        if (! is_string($locale) || ! Locales::isSupported($locale)) {
            abort(404);
        }

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        Cookie::queue('locale', $locale, 60 * 24 * 365);

        View::share('currentLocale', $locale);

        return $next($request);
    }
}
