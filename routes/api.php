<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Buyer\BuyerOrderController;
use App\Http\Controllers\Buyer\BuyerSellerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Buyer\BuyerProductController;
use App\Http\Controllers\Product\ProductCartController;
use App\Http\Controllers\Product\ProductBuyerController;
use App\Http\Controllers\Category\CategoryBuyerController;
use App\Http\Controllers\Category\CategoryProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::resource('products.categories', ProductController::class)->only([
//     'index','update', 'destroy'
// ]);


//Product Routes
Route::resource('products', ProductController::class);
Route::resource('products.buyers', ProductBuyerController::class)->only(['index']);



//Cart Routes
Route::post('products/add_to_cart/{id}/{qty?}', [ProductCartController::class, 'addToCart']);
Route::post('products/remove_from_cart/{id}', [ProductCartController::class, 'removeFromCart']);


//Buyer Routes

Route::resource('buyers', BuyerController::class);

Route::resource('products.buyers', ProductBuyerController::class)->only(['index']);

Route::resource('buyers.products', BuyerProductController::class)->only(['index']);

Route::resource('buyers.sellers', BuyerSellerController::class);

Route::resource('buyers.orders', BuyerOrderController::class);


//Category routes


Route::resource('categories', CategoryController::class);
Route::resource('categories.buyers', CategoryBuyerController::class);
Route::resource('categories.products', CategoryProductController::class);


