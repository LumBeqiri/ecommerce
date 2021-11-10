<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Product\ProductController;

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

Route::resource('products', ProductController::class);


Route::get('products/add_to_cart/{id}/{qty?}', [ProductController::class, 'addToCart']);
Route::get('products/remove_from_cart/{id}', [ProductController::class, 'removeFromCart']);

