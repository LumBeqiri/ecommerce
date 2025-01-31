<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;



use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function() {

            Route::middleware(['api'])
            ->prefix('api')
            ->group(base_path('routes/api.php'));

            Route::middleware(['api', 'admin'])
                ->prefix('api/admin')
                ->group(base_path('routes/admin.php'));
            
            // Vendor routes
            Route::middleware(['api', 'vendorAuthorization'])
                ->prefix('api/vendor')
                ->group(base_path('routes/vendor.php'));
            
            // Staff routes
            Route::middleware(['api'])
                ->prefix('api/staff')
                ->group(base_path('routes/staff.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // 'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
        $middleware->alias(['admin' => \App\Http\Middleware\Admin::class, 'vendorAuthorization' => \App\Http\Middleware\VendorAuthorization::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
       
    })
    ->create();
