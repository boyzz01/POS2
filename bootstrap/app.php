<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Customer guard: unauthenticated → /login (storefront login, bukan Filament)
        // Filament handles its own /admin/login redirect via its own middleware stack.
        $middleware->redirectGuestsTo('/login');

        // Alias middleware untuk API
        $middleware->alias([
            'kasir.only' => \App\Http\Middleware\KasirOnly::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
