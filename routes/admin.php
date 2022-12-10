<?php

use App\Http\Controllers\Admin\Product\AdminProductController;
use App\Http\Controllers\Admin\Users\AdminUserController;



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('users', AdminUserController::class);
    Route::post('products/{product}/restore', [AdminProductController::class , 'restore']);
    Route::resource('products', AdminProductController::class);

});

