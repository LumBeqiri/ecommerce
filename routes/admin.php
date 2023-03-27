<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Cart\AdminCartController;
use App\Http\Controllers\Admin\Users\AdminUserController;
use App\Http\Controllers\Admin\Region\AdminRegionController;
use App\Http\Controllers\Admin\Product\AdminProductController;
use App\Http\Controllers\Admin\Variant\AdminVariantController;
use App\Http\Controllers\Admin\Category\AdminCategoryController;
use App\Http\Controllers\Admin\TaxProvider\AdminTaxProviderController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('users', AdminUserController::class);
    Route::delete('products/{product}/categories/{category}', [AdminProductController::class, 'delete_product_category']);
    Route::resource('products', AdminProductController::class);

    Route::put('variants/{variant}', [AdminVariantController::class, 'update']);
    Route::delete('variants/{variant}', [AdminVariantController::class, 'destroy']);

    Route::get('carts', [AdminCartController::class, 'index']);
    Route::put('carts/{cart}', [AdminCartController::class, 'update']);
    Route::delete('carts/{cart}/variants/{variant}', [AdminCartController::class, 'remove_from_cart']);
    Route::delete('carts/{cart}', [AdminCartController::class, 'destroy']);

    Route::resource('categories', AdminCategoryController::class);
    Route::put('regions/{region}/updateCountries', [AdminRegionController::class, 'updateCountries']);
    Route::delete('regions/{region}/removeCountries', [AdminRegionController::class, 'removeCountries']);
    Route::resource('regions', AdminRegionController::class);
    Route::resource('tax_providers', AdminTaxProviderController::class);
});
