<?php

use App\Models\User;
use App\Models\Staff;
use App\values\Roles;
use App\Models\Region;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\TaxProvider;
use Illuminate\Support\Facades\Bus;
use Database\Seeders\CurrencySeeder;
use Illuminate\Support\Facades\Notification;
use Database\Seeders\RoleAndPermissionSeeder;
use App\Http\Controllers\Profile\ProfileController;


beforeEach(function () {
    $this->seed(CurrencySeeder::class);
    $this->seed(RoleAndPermissionSeeder::class);
    Notification::fake();
    Bus::fake();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
});

test('user can update their basic profile information', function () {
    $user = User::factory()->create();
    login($user);

    $profileData = [
        'first_name' => 'John',
        'last_name'  => 'Updated',
        'email'      => 'john.updated@example.com',
    ];

    $response = $this->putJson(action([ProfileController::class, 'update']), $profileData);

    $response->assertOk();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'first_name' => 'John',
        'last_name' => 'Updated',
        'email' => 'john.updated@example.com',
    ]);
});

test('vendor can update their profile information', function () {
    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::VENDOR);

    login($user);

    $profileData = [
        'first_name' => 'Vendor',
        'last_name'  => 'Updated',
        'email'      => 'vendor.updated@example.com',
        'vendor_name' => 'Updated Vendor Company',
        'city' => 'New City',
        'country_id' => Country::first()->id,
        'website' => 'https://updated-vendor.com',
    ];

    $response = $this->putJson(action([ProfileController::class, 'update']), $profileData);

    $response->assertOk();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'first_name' => 'Vendor',
        'last_name' => 'Updated',
        'email' => 'vendor.updated@example.com',
    ]);

    $this->assertDatabaseHas('vendors', [
        'id' => $vendor->id,
        'vendor_name' => 'Updated Vendor Company',
        'city' => 'New City',
        'country_id' => Country::first()->id,
        'website' => 'https://updated-vendor.com',
    ]);
});

test('staff can update their profile information', function () {
    $user = User::factory()->create();
    $staff = Staff::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::STAFF);

    login($user);

    $profileData = [
        'first_name' => 'Staff',
        'last_name'  => 'Updated',
        'email'      => 'staff.updated@example.com',
        'address' => 'New Address 123',
    ];

    $response = $this->putJson(action([ProfileController::class, 'update']), $profileData);

    $response->assertOk();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'first_name' => 'Staff',
        'last_name' => 'Updated',
        'email' => 'staff.updated@example.com',
    ]);

    $this->assertDatabaseHas('staff', [
        'id' => $staff->id,
        'address' => 'New Address 123',
    ]);
});

test('user cannot update profile with invalid email', function () {
    $user = User::factory()->create();
    login($user);

    $profileData = [
        'first_name' => 'John',
        'last_name'  => 'Doe',
        'email'      => 'invalid-email',
    ];

    $response = $this->putJson(action([ProfileController::class, 'update']), $profileData);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

