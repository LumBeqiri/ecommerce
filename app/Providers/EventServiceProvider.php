<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use App\Observers\VariantObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Product::observe(ProductObserver::class);
        Variant::observe(VariantObserver::class);
        User::observe(UserObserver::class);
    }
}
