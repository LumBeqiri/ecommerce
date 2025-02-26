<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware(['api'])
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware(['api', 'admin'])
                ->prefix('api/admin')
                ->group(base_path('routes/admin.php'));

            // Vendor routes
            Route::middleware(['api', 'VendorAuthorization'])
                ->prefix('api/vendor')
                ->group(base_path('routes/vendor.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // 'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
        $middleware->alias(['admin' => \App\Http\Middleware\Admin::class, 'VendorAuthorization' => \App\Http\Middleware\VendorAuthorization::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
