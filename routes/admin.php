<?php

use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\Users\AdminUserController;
use Illuminate\Support\Facades\Auth;


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('users', AdminUserController::class);

});

