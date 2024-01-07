<?php


use App\Http\Controllers\Vendor\VendorProductController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('products', [VendorProductController::class ,'store']);
    Route::post('products', [VendorProductController::class ,'update']);
  
});
