<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
        ]);
        // Guest yang akses route ber-auth diarahkan ke login Google
        // (project tidak punya route bernama "login" → tanpa ini akan 500)
        $middleware->redirectGuestsTo(fn () => route('google.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
