<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Variant;
use App\Policies\ProductPolicy;
use App\Policies\VariantPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Variant::class => VariantPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function($user, $abilty){
            return $user->hasRole('admin') ? true : null;
        });
    }
}
