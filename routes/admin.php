<?php

use App\Http\Controllers\Admin\TestController;
use Illuminate\Support\Facades\Auth;


Route::group(['middleware' => ['auth:sanctum']], function () {
    //admin routes
});

