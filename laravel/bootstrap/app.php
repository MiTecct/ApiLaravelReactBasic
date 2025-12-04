<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Pastikan rute API juga terdaftar
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // MENAMBAHKAN PENGECUALIAN CSRF UNTUK SEMUA RUTE API AUTH
        $middleware->validateCsrfTokens(except: [
            'api/login',
            'api/register',
            'api/*', // Opsional: Kecualikan semua rute API
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();