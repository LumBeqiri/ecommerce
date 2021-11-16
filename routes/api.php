<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Buyer\BuyerOrderController;
use App\Http\Controllers\Buyer\BuyerSellerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Order\OrderSellerController;
use App\Http\Controllers\Buyer\BuyerProductController;
use App\Http\Controllers\Order\OrderCategoryController;
use App\Http\Controllers\Product\ProductCartController;
use App\Http\Controllers\Product\ProductBuyerController;
use App\Http\Controllers\Product\ProductOrderController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Category\CategoryBuyerController;
use App\Http\Controllers\Category\CategoryOrderController;
use App\Http\Controllers\Category\CategorySellerController;
use App\Http\Controllers\Product\ProductCategoryController;
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
Route::resource('products.categories', ProductCategoryController::class)->only(['index']);
Route::resource('products.orders', ProductOrderController::class)->only(['index']);



//Cart Routes
Route::post('products/add_to_cart/{id}/{qty?}', [ProductCartController::class, 'addToCart']);
Route::post('products/remove_from_cart/{id}', [ProductCartController::class, 'removeFromCart']);


//Buyer Routes

Route::resource('buyers', BuyerController::class);

Route::resource('products.buyers', ProductBuyerController::class)->only(['index']);

Route::resource('buyers.products', BuyerProductController::class)->only(['index']);

Route::resource('buyers.sellers', BuyerSellerController::class);

Route::resource('buyers.orders', BuyerOrderController::class);

Route::get('buyer_cart', [ProductCartController::class, 'getCart']);

//Seller Routes


Route::resource('sellers', SellerController::class);
Route::resource('sellers.products', SellerProductController::class);


//Category routes


Route::resource('categories', CategoryController::class);
Route::resource('categories.buyers', CategoryBuyerController::class);
Route::resource('categories.products', CategoryProductController::class);
Route::resource('categories.sellers', CategorySellerController::class);
Route::resource('categories.orders', CategoryOrderController::class);


//Order Routes

Route::resource('orders', OrderController::class);
Route::resource('orders.categories', OrderCategoryController::class);
Route::resource('orders.sellers', OrderSellerController::class);



