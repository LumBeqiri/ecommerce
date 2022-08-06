<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // User::created(function($user){
        //     retry(5, function() use ($user){
        //         Mail::to($user)->send(new UserCreated($user));
        //     },100);
        // });

        // User::updated(function($user){
        //     if($user->isDirty('email')){
        //         retry(5, function() use ($user){
        //             Mail::to($user)->send(new UserMailChanged($user));
        //         },100);
        //     }
        // });

        // Product::updated(function($product){
        //     if($product->stock == 0 && $product->isAvaliable()){
        //         $product->status = Product::UNAVAILABLE_PRODUCT;

        //         $product->save();
        //     }
        // });
    }
}
