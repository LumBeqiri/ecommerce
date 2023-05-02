<?php

use App\Http\Controllers\Admin\Cart\AdminCartController;
use App\Http\Controllers\Admin\Category\AdminCategoryController;
use App\Http\Controllers\Admin\Discount\DiscountConditionController;
use App\Http\Controllers\Admin\Discount\DiscountController;
use App\Http\Controllers\Admin\Product\AdminProductController;
use App\Http\Controllers\Admin\Product\AdminVariantAttributeController;
use App\Http\Controllers\Admin\Product\AdminVariantController;
use App\Http\Controllers\Admin\Product\AdminVariantMediaController;
use App\Http\Controllers\Admin\Region\AdminRegionController;
use App\Http\Controllers\Admin\TaxProvider\AdminTaxProviderController;
use App\Http\Controllers\Admin\Users\AdminUserController;
use App\Http\Controllers\Product\ProductThumbnailController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('users', AdminUserController::class);
    Route::get('products', [AdminProductController::class, 'index']);
    Route::post('products', [AdminProductController::class, 'store']);
    Route::put('products/{product}', [AdminProductController::class, 'update']);
    Route::delete('products/{product}', [AdminProductController::class, 'destroy']);

    Route::post('products/{product}/thumbnail', [ProductThumbnailController::class, 'store']);
    Route::delete('products/{product}/thumbnail', [ProductThumbnailController::class, 'destroy']);

    Route::get('variants/{variant}/attributes', [AdminVariantAttributeController::class, 'show']);
    Route::post('variants/{variant}/attributes', [AdminVariantAttributeController::class, 'store']);
    Route::delete('variants/{variant}/attributes/{attribute}', [AdminVariantAttributeController::class, 'destroy']);

    Route::get('variants/{variant}/medias', [AdminVariantMediaController::class, 'index']);
    Route::post('variants/{variant}/medias', [AdminMediaController::class, 'store']);
    Route::delete('variants/{variant}/medias/{media}', [AdminVariantMediaController::class, 'destroy']);

    Route::post('products/{product}/variants', [AdminVariantController::class, 'store']);
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

    Route::get('discounts', [DiscountController::class, 'index']);
    Route::post('discounts', [DiscountController::class, 'store']);
    Route::get('discounts/{discount}', [DiscountController::class, 'show']);
    Route::put('discounts/{discount}', [DiscountController::class, 'update']);
    Route::delete('discounts/{discount}', [DiscountController::class, 'destroy']);
    Route::post('discount-conditions/{discount}', [DiscountConditionController::class, 'store']);
    Route::get('discount-conditions/{discount_condition}', [DiscountConditionController::class, 'show']);
    Route::put('discount-conditions/{discount_condition}', [DiscountConditionController::class, 'update']);
    Route::delete('discount-conditions/{discount_condition}', [DiscountConditionController::class, 'destroy']);
    Route::delete('discount-conditions/{discount_condition}/product/{product}', [DiscountConditionController::class, 'removeProduct']);
    Route::delete('discount-conditions/{discount_condition}/product/{customerGroup}', [DiscountConditionController::class, 'removeCustomerGroup']);
});
