<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            \Illuminate\Support\Facades\Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/web.php'));

            \Illuminate\Support\Facades\Route::middleware('api')
                ->prefix('api')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/api.php'));
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\RedirectIfNotAdmin::class,
            'admin.guest' => \App\Http\Middleware\RedirectIfAdmin::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'wartawan' => \App\Http\Middleware\RedirectIfNotWartawan::class,
            'wartawan.guest' => \App\Http\Middleware\RedirectIfWartawan::class,
            'auth.legacy' => \App\Http\Middleware\RedirectIfNotAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
