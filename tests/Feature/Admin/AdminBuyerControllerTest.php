<?php

use App\Models\User;
use App\Models\Buyer;
use App\Models\Region;
use App\Models\Country;
use App\Models\Currency;
use App\Models\TaxProvider;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Database\Seeders\RoleAndPermissionSeeder;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\Users\AdminUserController;
use App\Http\Controllers\Admin\Buyer\AdminBuyerController;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    Notification::fake();
    Bus::fake();
    Currency::factory()->count(5)->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
});


it('admin can update buyer first name and last name', function () {
    Country::factory()->create();
    User::factory()->create();
    $userA = Buyer::factory()->create(['first_name' => 'lumbo', 'last_name' => 'test']);
    $updatedFirstName = 'drin';
    $updatedLastName = 'samsung';
    $admin = User::factory()->create(['email' => 'jon@test.com']);
    $admin->assignRole('admin');
    login($admin);

    $response = $this->putJson(action([AdminBuyerController::class, 'update'], $userA->uuid), [
        'first_name' => $updatedFirstName,
        'last_name' => $updatedLastName,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Buyer::class, ['first_name' => $updatedFirstName, 'last_name' => $updatedLastName]);
});

it('admin can update buyer city', function () {
    Country::factory()->create();
    User::factory()->create();
    $userA = Buyer::factory()->create(['city' => 'Amber']);
    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = 'Tirana';
    login($user);

    $response = $this->putJson(action([AdminBuyerController::class, 'update'], $userA->uuid), [
        'city' => $updated,
    ]);


    $response->assertOk();

    $this->assertDatabaseHas(Buyer::class, ['city' => $updated]);
});

it('admin can update buyer country', function () {
    $old_country = Country::factory()->create();
    User::factory()->create();
    $userA = Buyer::factory()->create(['country_id' => $old_country->id]);
    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $new_country = Country::factory()->create();

    login($user);

    $response = $this->putJson(action([AdminBuyerController::class, 'update'], $userA->uuid), [
        'country_id' => $new_country->id,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Buyer::class, ['country_id' => $new_country->id]);
});

it('admin can update buyer phone', function () {
    User::factory()->create();
    $userA = Buyer::factory()->create(['phone' => '03212']);
    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = '123456';
    login($user);

    $response = $this->putJson(action([AdminBuyerController::class, 'update'], $userA->uuid), [
        'phone' => $updated,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Buyer::class, ['phone' => $updated]);
});

