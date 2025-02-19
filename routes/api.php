<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\Cart\CartController;
use App\Http\Controllers\User\Order\OrderController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\User\Profile\ProfileController;
use App\Http\Controllers\User\UserAccount\UserController;
use App\Http\Controllers\Public\Country\CountryController;
use App\Http\Controllers\Public\Product\ProductController;
use App\Http\Controllers\Public\Variant\VariantController;
use App\Http\Controllers\User\Checkout\CheckoutController;
use App\Http\Controllers\Auth\Buyer\RegisterBuyerController;
use App\Http\Controllers\User\Products\UserProductController;
use App\Http\Controllers\User\Variants\UserVariantController;
use App\Http\Controllers\CustomerGroup\CustomerGroupController;
use App\Http\Controllers\Public\Product\ProductCategoryController;
use App\Http\Controllers\User\Variants\UserVariantMediaController;
use App\Http\Controllers\User\Variants\UserVariantPriceController;
use App\Http\Controllers\Public\Category\CategoryProductController;
use App\Http\Controllers\User\Products\UserProductThumbnailController;
use App\Http\Controllers\User\Variants\UserVariantAttributeController;


Route::post('login', LoginController::class)->name('login');

Route::post('register-buyer', RegisterBuyerController::class)->name('register-buyer');
Route::post('register', [RegisterController::class, 'register'])->name('register');

Route::post('forgot_password', [ForgotPasswordController::class, 'reset_link'])->name('reset.link');
Route::post('reset_password', [ForgotPasswordController::class, 'reset_password'])->name('password.reset');

Route::name('verify')->get('users/verify/{token}', [UserController::class, 'verify']);
Route::name('resend')->get('users/{user}/resend', [UserController::class, 'resend']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::put('change_password', ChangePasswordController::class)->name('change_password');

    Route::group(['prefix' => 'user'], function () {

        Route::put('profile', [ProfileController::class, 'update']);

        Route::get('products', [UserProductController::class, 'index']);
        Route::post('products', [UserProductController::class, 'store']);
        Route::get('products/{product}', [UserProductController::class, 'show']);
        Route::put('products/{product}', [UserProductController::class, 'update']);
        Route::delete('products/{product}', [UserProductController::class, 'destroy']);

        Route::post('products/{product}/thumbnail', [UserProductThumbnailController::class, 'store']);
        Route::delete('products/{product}/thumbnail', [UserProductThumbnailController::class, 'destroy']);

        Route::post('variants', [UserVariantController::class, 'store']);
        Route::get('variants/{variant}', [UserVariantController::class, 'show']);
        Route::put('variants/{variant}', [UserVariantController::class, 'update']);
        Route::delete('variants/{variant}', [UserVariantController::class, 'destroy']);

        Route::post('variants/{variant}/prices', [UserVariantPriceController::class, 'store']);
        Route::put('variants/{variant}/prices/{variantPrice}', [UserVariantPriceController::class, 'update']);
        Route::delete('variants/{variant}/prices/{variantPrice}', [UserVariantPriceController::class, 'destroy']);

        Route::get('variants/{variant}/attributes', [UserVariantAttributeController::class, 'index']);
        Route::put('variants/{variant}/attributes', [UserVariantAttributeController::class, 'update']);

        Route::get('variants/{variant}/medias', [UserVariantMediaController::class, 'index']);
        Route::post('variants/{variant}/medias', [UserVariantMediaController::class, 'store']);
        Route::delete('variants/{variant}/medias/{media_uuid}', [UserVariantMediaController::class, 'destroy']);

        Route::get('carts', [CartController::class, 'index']);
        Route::put('carts/{cart}', [CartController::class, 'update']);
        Route::post('sync-cart', [CartController::class, 'syncCart']);
        Route::get('carts/{cart}', [CartController::class, 'show']);
        Route::delete('carts/{cart}', [CartController::class, 'destroy']);

        Route::post('carts/add', [CartController::class, 'add_to_cart']);
        Route::post('carts/remove', [CartController::class, 'remove_from_cart']);
        Route::post('carts/apply-discount', [CartController::class, 'apply_discount']);

        Route::post('customer-groups', [CustomerGroupController::class, 'store']);
        Route::get('customer-groups', [CustomerGroupController::class, 'index']);
        Route::get('customer-groups/{customerGroup}', [CustomerGroupController::class, 'show']);
        Route::delete('customer-groups/{customerGroup}', [CustomerGroupController::class, 'destroy']);

        Route::post('checkout', [CheckoutController::class, 'store']);
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{order}', [OrderController::class, 'show']);
        Route::put('orders/{order}', [OrderController::class, 'update']);
        Route::delete('orders/{order}', [OrderController::class, 'destroy']);

        Route::get('products/{product}/categories', [ProductCategoryController::class, 'index']);
        Route::get('users/{user}', [UserController::class, 'show']);
        Route::post('users', [UserController::class, 'store']);
        Route::delete('users/{user}', [UserController::class, 'destroy']);
    });

});

// Public Routes
Route::get('countries', [CountryController::class, 'index']);

Route::get('products/{product}', [ProductController::class, 'show']);
Route::get('products', [ProductController::class, 'index']);

Route::delete('products/deleteCategories/{product}', [ProductCategoryController::class, 'deleteCategories']);

Route::get('variants', [VariantController::class, 'index']);
Route::get('variants/{variant}', [VariantController::class, 'show']);

Route::resource('categories.products', CategoryProductController::class);
Route::get('categories/{category}/subs', [CategoryProductController::class, 'subcats']);
