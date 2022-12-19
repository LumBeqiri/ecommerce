<?php

use App\Http\Controllers\Admin\Cart\AdminCartController;
use App\Http\Controllers\Admin\Category\AdminCategoryController;
use App\Http\Controllers\Admin\Product\AdminProductController;
use App\Http\Controllers\Admin\Users\AdminUserController;
use App\Http\Controllers\Admin\Variant\AdminVariantController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('users', AdminUserController::class);
    Route::delete('products/{product}/categories/{category}', [AdminProductController::class, 'delete_product_category']);
    Route::resource('products', AdminProductController::class);

    Route::put('variants/{variant}', [AdminVariantController::class, 'update']);
    Route::delete('variants/{variant}', [AdminVariantController::class, 'destroy']);

    Route::get('carts', [AdminCartController::class, 'index']);
    Route::delete('carts/{cart}/variants/{variant}', [AdminCartController::class, 'remove_from_cart']);
    Route::delete('carts/{cart}', [AdminCartController::class, 'destroy']);

    Route::resource('categories', AdminCategoryController::class);

});

