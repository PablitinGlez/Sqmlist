<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware; // Mantener si aún lo aliasas o usas en otros lugares
use App\Http\Middleware\RedirectGuestFromAdmin; // Importar tu nuevo middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias de middlewares (si los necesitas para Route::middleware())
        $middleware->alias([
            'admin' => AdminMiddleware::class, // Mantener si lo usas en rutas no-Filament
            'redirect.guest.admin' => RedirectGuestFromAdmin::class, // Alias para este middleware
        ]);

        // **AÑADIR ESTE MIDDLEWARE AL PRINCIPIO DEL GRUPO 'web'**
        // Esto asegura que se ejecute ANTES que Filament intente redirigir.
        $middleware->prependToGroup('web', RedirectGuestFromAdmin::class);

        // Puedes comentar o eliminar los demás $middleware->web() si solo usas prependToGroup para esto
        // $middleware->web([
        //    // Otros middlewares que quieras aplicar globalmente al grupo web
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
