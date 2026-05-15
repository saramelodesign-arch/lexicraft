<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

final class ResolvePreferredLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Locales::preferredFromRequest($request);

        app()->setLocale($locale);

        if ($request->hasSession()) {
            $request->session()->put('locale', $locale);
        }

        Cookie::queue('locale', $locale, 60 * 24 * 365);
        View::share('currentLocale', $locale);

        return $next($request);
    }
}
