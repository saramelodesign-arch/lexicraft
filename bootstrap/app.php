<?php

use App\Http\Middleware\EnsureUserCanAccessAdmin;
use App\Http\Middleware\ResolvePreferredLocale;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocaleFromRoute;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(prepend: [
            SecurityHeaders::class,
            ResolvePreferredLocale::class,
        ]);

        $middleware->alias([
            'locale' => SetLocaleFromRoute::class,
            'admin' => EnsureUserCanAccessAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
