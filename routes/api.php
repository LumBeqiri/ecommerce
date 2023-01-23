<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Buyer\BuyerCartController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Variant\VariantController;
use App\Http\Controllers\Buyer\BuyerOrderController;
use App\Http\Controllers\Buyer\BuyerSellerController;
use App\Http\Controllers\Order\OrderSellerController;
use App\Http\Controllers\Buyer\BuyerProductController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Order\OrderCategoryController;
use App\Http\Controllers\Attributes\AttributeController;
use App\Http\Controllers\Product\ProductBuyerController;
use App\Http\Controllers\Product\ProductOrderController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerVariantController;
use App\Http\Controllers\Category\CategoryBuyerController;
use App\Http\Controllers\Category\CategoryOrderController;
use App\Http\Controllers\Category\CategorySellerController;
use App\Http\Controllers\Product\ProductCategoryController;
use App\Http\Controllers\Category\CategoryProductController;
use App\Http\Controllers\CustomerGroup\CustomerGroupController;
use App\Http\Controllers\Product\ProductThumbnailController;
use App\Http\Controllers\Seller\SellerVariantMediaController;
use App\Http\Controllers\Seller\SellerVariantAttributeController;

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



Route::post('login', LoginController::class)->name('login');


// Route::resource('products.categories', ProductController::class)->only([
//     'index','update', 'destroy'
// ]);

Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::post('forgot_password', [ForgotPasswordController::class, 'reset_link'])->name('reset.link');
Route::post('reset_password', [ForgotPasswordController::class, 'reset_password'])->name('password.reset');


//Cart

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::put('change_password', ChangePasswordController::class)->name('change_password');
    Route::post('sellers/{seller}/products', [SellerProductController::class, 'store']);
    Route::put('sellers/{seller}/products/{product}', [SellerProductController::class, 'update']);

    Route::post('variants/{variant}/attributes', [SellerVariantAttributeController::class, 'store']);
    Route::delete('variants/{variant}/attributes/{attribute}', [SellerVariantAttributeController::class, 'destroy']);
    

    Route::get('variants/{variant}/medias', [SellerVariantMediaController::class, 'index']);
    Route::post('variants/{variant}/medias', [SellerVariantMediaController::class, 'store']);
    Route::delete('variants/{variant}/medias/{media}', [SellerVariantMediaController::class, 'destroy']);

    Route::post('customer-groups', [CustomerGroupController::class, 'store']);
    Route::get('customer-groups', [CustomerGroupController::class, 'index']);
    Route::get('customer-groups/{customerGroup}', [CustomerGroupController::class, 'show']);
    Route::delete('customer-groups/{customerGroup}', [CustomerGroupController::class, 'destroy']);

    Route::post('products/{product}/thumbnail', [ProductThumbnailController::class, 'store']);
    Route::delete('products/{product}/thumbnail', [ProductThumbnailController::class, 'destroy']);

    Route::resource('carts', CartController::class);

    Route::get('buyer/{buyer}/cart', [BuyerCartController::class, 'index']);
    Route::post('store_cart', [BuyerCartController::class, 'store']);
    Route::post('add_to_cart', [BuyerCartController::class, 'add_to_cart']);
    Route::delete('remove_from_cart', [BuyerCartController::class, 'remove_from_cart']);

    Route::post('products/{product}/variants', [SellerVariantController::class, 'store']);
    Route::put('variants/{variant}', [SellerVariantController::class, 'update']);
    Route::delete('variants/{variant}', [SellerVariantController::class, 'destroy']);

});

//Product Routes
//Route::post('products/delete_categories/{product}', [ProductCategoryController::class, 'deleteCategories']);
Route::resource('products', ProductController::class);
Route::resource('products.buyers', ProductBuyerController::class);
Route::resource('products.categories', ProductCategoryController::class);
Route::resource('products.orders', ProductOrderController::class);
Route::delete('products/deleteCategories/{product}', [ProductCategoryController::class, 'deleteCategories']);


// VARIANT Routes
Route::resource('variants', VariantController::class)->only(['index', 'show']);

// ATTRIUBTE Routes

Route::get('attributes', [AttributeController::class, 'index']);
Route::get('attributes/{attribute}', [AttributeController::class, 'show']);
Route::post('attributes', [AttributeController::class, 'store']);
Route::put('attributes/{attribute}', [AttributeController::class, 'update']);


//Buyer Routes

Route::resource('buyers', BuyerController::class);

//Route::resource('products.buyers', ProductBuyerController::class)->only(['index']);

Route::resource('buyers.products', BuyerProductController::class)->only(['index']);

Route::resource('buyers.sellers', BuyerSellerController::class);

Route::resource('buyers.orders', BuyerOrderController::class);

// Route::get('buyer_cart', [ProductCartController::class, 'getCart']);

//Seller Routes

Route::resource('sellers', SellerController::class);



//Category routes

Route::resource('categories.buyers', CategoryBuyerController::class);
Route::resource('categories.products', CategoryProductController::class);
Route::get('categories/{category}/subs', [CategoryProductController::class,'subcats']);
Route::resource('categories.sellers', CategorySellerController::class);
Route::resource('categories.orders', CategoryOrderController::class);


//Order Routes

Route::resource('orders', OrderController::class);
Route::resource('orders.categories', OrderCategoryController::class);
Route::resource('orders.sellers', OrderSellerController::class);


//User Routes

Route::resource('users', UserController::class);

//Verify account

Route::name('verify')->get('users/verify/{token}', [UserController::class, 'verify']);
Route::name('resend')->get('users/{user}/resend', [UserController::class, 'resend']);


