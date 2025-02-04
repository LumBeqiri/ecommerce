<?php

use App\Http\Controllers\Staff\StaffVariantAttributeController;
use App\Http\Controllers\Staff\StaffVariantController;
use App\Http\Controllers\Staff\StaffVariantPriceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('variants', [StaffVariantController::class, 'store']);
    Route::get('variants/{variant}', [StaffVariantController::class, 'show']);
    Route::put('variants/{variant}', [StaffVariantController::class, 'update']);
    Route::delete('variants/{variant}', [StaffVariantController::class, 'destroy']);

    Route::get('variants/{variant}/attributes', [StaffVariantAttributeController::class, 'index']);
    Route::put('variants/{variant}/attributes', [StaffVariantAttributeController::class, 'update']);

    Route::post('variants/{variant}/prices', [StaffVariantPriceController::class, 'store']);
    Route::put('variants/{variant}/prices/{variantPrice}', [StaffVariantPriceController::class, 'update']);
    Route::delete('variants/{variant}/prices/{variantPrice}', [StaffVariantPriceController::class, 'destroy']);

});
