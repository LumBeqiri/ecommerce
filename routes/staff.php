<?php

use App\Http\Controllers\Staff\StaffVariantAttributeController;
use App\Http\Controllers\Staff\StaffVariantPriceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('variants/{variant}/attributes', [StaffVariantAttributeController::class, 'index']);
    Route::put('variants/{variant}/attributes', [StaffVariantAttributeController::class, 'update']);
});
