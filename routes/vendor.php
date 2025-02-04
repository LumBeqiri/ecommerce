<?php

use App\Http\Controllers\Admin\Product\AdminVariantMediaController;
use App\Http\Controllers\Auth\Staff\RegisterStaffController;
use App\Http\Controllers\Product\ProductThumbnailController;
use App\Http\Controllers\Vendor\VendorPermissionManagerController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorVariantAttributeController;
use App\Http\Controllers\Vendor\VendorVariantController;
use App\Http\Controllers\Vendor\VendorVariantPriceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('products/{product}/thumbnail', [ProductThumbnailController::class, 'store']);
    Route::delete('products/{product}/thumbnail', [ProductThumbnailController::class, 'destroy']);

    Route::post('variants', [VendorVariantController::class, 'store']);
    Route::get('variants/{variant}', [VendorVariantController::class, 'show']);
    Route::put('variants/{variant}', [VendorVariantController::class, 'update']);
    Route::delete('variants/{variant}', [VendorVariantController::class, 'destroy']);

    Route::get('variants/{variant}/medias', [AdminVariantMediaController::class, 'index']);
    Route::post('variants/{variant}/medias', [AdminVariantMediaController::class, 'store']);
    Route::delete('variants/{variant}/medias/{media_id}', [AdminVariantMediaController::class, 'destroy']);

    Route::get('variants/{variant}/attributes', [VendorVariantAttributeController::class, 'index']);
    Route::put('variants/{variant}/attributes', [VendorVariantAttributeController::class, 'update']);

    Route::post('variants/{variant}/prices', [VendorVariantPriceController::class, 'store']);
    Route::put('variants/{variant}/prices/{variantPrice}', [VendorVariantPriceController::class, 'update']);
    Route::delete('variants/{variant}/prices/{variantPrice}', [VendorVariantPriceController::class, 'destroy']);

    Route::get('user-permissions', [VendorPermissionManagerController::class, 'index']);
    Route::put('users/{user}/permissions', [VendorPermissionManagerController::class, 'update']);
    Route::delete('users/{user}/permissions/{permission_id}', [VendorPermissionManagerController::class, 'destroy']);

    Route::post('register-staff', RegisterStaffController::class)->name('register-staff');

});
