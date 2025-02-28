<?php

use App\Http\Controllers\Admin\Attributes\AdminAttributeController;
use App\Http\Controllers\Admin\Buyer\AdminBuyerController;
use App\Http\Controllers\Admin\Cart\AdminCartController;
use App\Http\Controllers\Admin\Category\AdminCategoryController;
use App\Http\Controllers\Admin\Discount\AdminDiscountController;
use App\Http\Controllers\Admin\Order\AdminOrderController;
use App\Http\Controllers\Admin\Product\AdminProductController;
use App\Http\Controllers\Admin\Product\AdminVariantAttributeController;
use App\Http\Controllers\Admin\Product\AdminVariantController;
use App\Http\Controllers\Admin\Product\AdminVariantMediaController;
use App\Http\Controllers\Admin\Product\AdminVariantPriceController;
use App\Http\Controllers\Admin\Region\AdminRegionController;
use App\Http\Controllers\Admin\TaxProvider\AdminTaxProviderController;
use App\Http\Controllers\Admin\Users\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('users', AdminUserController::class);
    Route::put('buyers/{buyer}', [AdminBuyerController::class, 'update']);
    Route::get('products', [AdminProductController::class, 'index']);
    Route::post('products', [AdminProductController::class, 'store']);
    Route::get('products/{product}', [AdminProductController::class, 'show']);
    Route::put('products/{product}', [AdminProductController::class, 'update']);
    Route::delete('products/{product}', [AdminProductController::class, 'destroy']);

    Route::get('variants/{variant}/attributes', [AdminVariantAttributeController::class, 'show']);
    Route::post('variants/{variant}/attributes', [AdminVariantAttributeController::class, 'store']);
    Route::delete('variants/{variant}/attributes/{attribute}', [AdminVariantAttributeController::class, 'destroy']);

    Route::get('variants/{variant}/medias', [AdminVariantMediaController::class, 'index']);
    Route::post('variants/{variant}/medias', [AdminVariantMediaController::class, 'store']);
    Route::delete('variants/{variant}/medias/{media}', [AdminVariantMediaController::class, 'destroy']);

    Route::post('products/{product}/variants', [AdminVariantController::class, 'store']);
    Route::put('variants/{variant}', [AdminVariantController::class, 'update']);
    Route::delete('variants/{variant}', [AdminVariantController::class, 'destroy']);

    Route::post('variants/{variant}/prices', [AdminVariantPriceController::class, 'store']);
    Route::put('variants/{variant}/prices/{variantPrice}', [AdminVariantPriceController::class, 'update']);
    Route::delete('variants/{variant}/prices/{variantPrice}', [AdminVariantPriceController::class, 'destroy']);

    Route::get('carts', [AdminCartController::class, 'index']);
    Route::put('carts/{cart}', [AdminCartController::class, 'update']);
    Route::delete('carts/{cart}/variants/{variant}', [AdminCartController::class, 'remove_from_cart']);
    Route::delete('carts/{cart}', [AdminCartController::class, 'destroy']);

    Route::resource('attributes', AdminAttributeController::class);

    Route::resource('categories', AdminCategoryController::class);
    Route::put('regions/{region}/updateCountries', [AdminRegionController::class, 'updateCountries']);
    Route::delete('regions/{region}/removeCountries', [AdminRegionController::class, 'removeCountries']);
    Route::resource('regions', AdminRegionController::class);
    Route::resource('tax_providers', AdminTaxProviderController::class);

    Route::get('orders', [AdminOrderController::class, 'index']);
    Route::put('orders/{order}', [AdminOrderController::class, 'update']);
    Route::delete('orders/{order}', [AdminOrderController::class, 'destroy']);

    Route::get('discounts', [AdminDiscountController::class, 'index']);
    Route::post('discounts', [AdminDiscountController::class, 'store']);
    Route::get('discounts/{discount}', [AdminDiscountController::class, 'show']);
    Route::put('discounts/{discount}', [AdminDiscountController::class, 'update']);
    Route::delete('discounts/{discount}', [AdminDiscountController::class, 'destroy']);
});
