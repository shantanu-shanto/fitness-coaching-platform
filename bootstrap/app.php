<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        // ...
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // ...
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'coach' => \App\Http\Middleware\IsCoach::class,
            'client' => \App\Http\Middleware\IsClient::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
