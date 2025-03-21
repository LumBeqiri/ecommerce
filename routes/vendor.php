<?php

use App\Http\Controllers\Auth\Staff\RegisterStaffController;
use App\Http\Controllers\User\Vendor\VendorPermissionManagerController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('user-permissions', [VendorPermissionManagerController::class, 'index']);
    Route::put('users/{user}/permissions', [VendorPermissionManagerController::class, 'update']);
    Route::delete('users/{user}/permissions/{permission_id}', [VendorPermissionManagerController::class, 'destroy']);

    Route::post('register-staff', RegisterStaffController::class)->name('register-staff');

});
