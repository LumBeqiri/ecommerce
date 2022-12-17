<?php

use App\Http\Controllers\Admin\Product\AdminProductController;
use App\Http\Controllers\Admin\Users\AdminUserController;
use App\Http\Controllers\Admin\Variant\AdminVariantController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('users', AdminUserController::class);
    Route::put('products/{product}/restore', [AdminProductController::class , 'restore']);
    Route::resource('products', AdminProductController::class);
    Route::put('variants/{variant}', [AdminVariantController::class, 'update']);
    Route::delete('variants/{variant}', [AdminVariantController::class, 'destroy']);

});

