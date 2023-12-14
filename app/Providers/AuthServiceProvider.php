<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use App\Models\CustomerGroup;
use App\Policies\ProductPolicy;
use App\Policies\VariantPolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\CustomerGroupPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        CustomerGroupPolicy::class => CustomerGroup::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


        // Gate::define('viewPulse', function (User $user) {
        //     return true;//$user->isAdmin();
        // });


        Gate::before(function ($user, $abilty) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
