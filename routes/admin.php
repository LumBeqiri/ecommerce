<?php

use App\Http\Controllers\Admin\Product\AdminProductController;
use App\Http\Controllers\Admin\Users\AdminUserController;



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('users', AdminUserController::class);
    Route::post('products/{product}/restore', [AdminProductController::class , 'restore']);
    Route::delete('products/{product}/force_delete', [AdminProductController::class , 'forceDelete']);
    Route::resource('products', AdminProductController::class);

});

