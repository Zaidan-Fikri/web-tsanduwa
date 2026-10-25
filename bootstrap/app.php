<?php

use App\Http\Middleware\AdminArea;
use App\Http\Middleware\AdminMenuAccess;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SuperAdminOnly;
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
        $middleware->web(append: [SecurityHeaders::class]);

        $middleware->alias([
            'admin.area' => AdminArea::class,
            'admin.menu' => AdminMenuAccess::class,
            'superadmin' => SuperAdminOnly::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
