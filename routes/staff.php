<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Vendor\StaffProductController;
use App\Http\Controllers\Vendor\StaffVariantController;
use App\Http\Controllers\Vendor\VendorVariantController;
use App\Http\Controllers\Vendor\StaffVariantPriceController;
use App\Http\Controllers\Vendor\VendorVariantPriceController;
use App\Http\Controllers\Vendor\StaffVariantAttributeController;

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('products', [StaffProductController::class, 'store']);
    Route::get('products/{product}', [StaffProductController::class, 'show']);
    Route::put('products/{product}', [StaffProductController::class, 'update']);
    Route::delete('products/{product}', [StaffProductController::class, 'destroy']);

    Route::post('variants', [StaffVariantController::class, 'store']);
    Route::get('variants/{variant}', [StaffVariantController::class, 'show']);
    Route::put('variants/{variant}', [StaffVariantController::class, 'update']);
    Route::delete('variants/{variant}', [StaffVariantController::class, 'destroy']);

    Route::get('variants/{variant}/attributes', [StaffVariantAttributeController::class, 'index']);
    Route::put('variants/{variant}/attributes', [StaffVariantAttributeController::class, 'update']);

    Route::post('variants/{variant}/prices', [StaffVariantPriceController::class, 'store']);
    Route::put('variants/{variant}/prices/{variantPrice}', [StaffVariantPriceController::class, 'update']);

});
