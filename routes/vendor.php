<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorVariantController;
use App\Http\Controllers\Vendor\VendorVariantAttributeController;

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('products', [VendorProductController::class, 'store']);
    Route::get('products/{product}', [VendorProductController::class, 'show']);
    Route::put('products/{product}', [VendorProductController::class, 'update']);
    Route::delete('products/{product}', [VendorProductController::class, 'destroy']);

    Route::post('variants', [VendorVariantController::class, 'store']);
    Route::get('variants/{variant}', [VendorVariantController::class, 'show']);
    Route::put('variants/{variant}', [VendorVariantController::class, 'update']);
    Route::delete('variants/{variant}', [VendorVariantController::class, 'destroy']);
    Route::get('variants/{variant}/attributes', [VendorVariantAttributeController::class, 'index']);


});
