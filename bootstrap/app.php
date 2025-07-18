<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RedirectGuestFromAdmin;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        $middleware->alias([
            'admin' => AdminMiddleware::class, 
            'redirect.guest.admin' => RedirectGuestFromAdmin::class, 
        ]);

       
        $middleware->prependToGroup('web', RedirectGuestFromAdmin::class);

    
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
