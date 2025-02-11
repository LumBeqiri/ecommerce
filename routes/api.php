<?php

use App\Http\Controllers\Auth\Buyer\RegisterBuyerController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Buyer\BuyerCartController;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Buyer\BuyerOrderController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\CartItem\CartItemController;
use App\Http\Controllers\Category\CategoryProductController;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\CustomerGroup\CustomerGroupController;
use App\Http\Controllers\Product\ProductCategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\User\Products\UserProductController;
use App\Http\Controllers\User\Products\UserProductThumbnailController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\Variants\UserVariantAttributeController;
use App\Http\Controllers\User\Variants\UserVariantController;
use App\Http\Controllers\User\Variants\UserVariantMediaController;
use App\Http\Controllers\User\Variants\UserVariantPriceController;
use App\Http\Controllers\Variant\VariantController;
use Illuminate\Support\Facades\Route;

Route::post('login', LoginController::class)->name('login');

Route::post('register-buyer', RegisterBuyerController::class)->name('register-buyer');
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::post('forgot_password', [ForgotPasswordController::class, 'reset_link'])->name('reset.link');
Route::post('reset_password', [ForgotPasswordController::class, 'reset_password'])->name('password.reset');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::put('change_password', ChangePasswordController::class)->name('change_password');

    Route::group(['prefix' => 'user'], function () {
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

    });

    Route::get('carts', [CartController::class, 'index']);
    Route::put('carts/{cart}', [CartController::class, 'update']);
    Route::post('carts/{cart}/discount', [CartController::class, 'apply_discount']);
    Route::get('carts/{cart}', [CartController::class, 'show']);
    Route::delete('carts/{cart}', [CartController::class, 'destroy']);

    Route::post('carts/{cart}/items', [CartItemController::class, 'add_to_cart']);
    Route::delete('carts/{cart}/items/{item}', [CartItemController::class, 'remove_from_cart']);

    Route::post('customer-groups', [CustomerGroupController::class, 'store']);
    Route::get('customer-groups', [CustomerGroupController::class, 'index']);
    Route::get('customer-groups/{customerGroup}', [CustomerGroupController::class, 'show']);
    Route::delete('customer-groups/{customerGroup}', [CustomerGroupController::class, 'destroy']);

    Route::get('cart', [BuyerCartController::class, 'index']);
    Route::post('cart/add', [BuyerCartController::class, 'add_to_cart']);
    Route::post('cart/remove', [BuyerCartController::class, 'remove_from_cart']);
    Route::post('cart/apply-discount', [BuyerCartController::class, 'apply_discount']);

    Route::post('buyer-orders', [BuyerOrderController::class, 'store']);
});

Route::get('countries', [CountryController::class, 'index']);

Route::resource('products', ProductController::class);
Route::resource('products.categories', ProductCategoryController::class);
Route::delete('products/deleteCategories/{product}', [ProductCategoryController::class, 'deleteCategories']);

Route::resource('variants', VariantController::class)->only(['index', 'show']);

Route::resource('buyers', BuyerController::class);

Route::resource('categories.products', CategoryProductController::class);
Route::get('categories/{category}/subs', [CategoryProductController::class, 'subcats']);

Route::resource('users', UserController::class);

Route::name('verify')->get('users/verify/{token}', [UserController::class, 'verify']);
Route::name('resend')->get('users/{user}/resend', [UserController::class, 'resend']);
