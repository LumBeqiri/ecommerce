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

it('admin can show users', function () {
    User::factory()->count(10)->create();
    $user = User::factory()->create();
    $user->assignRole('admin');

    login($user);

    $response = $this->getJson(action([AdminUserController::class, 'index']));

    $response->assertOk();
});

it('admin can create user', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    login($user);

    $password = $this->faker()->password(8, 12);

    $response = $this->postJson(action([AdminUserController::class, 'store']), [
        'name' => $this->faker()->name(),
        'email' => $this->faker()->email(),
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertStatus(200);

    $user_id = $response->json('id');

    $this->assertDatabaseHas(User::class, ['uuid' => $user_id]);
});



it('admin can change user password', function () {
    $userA = User::factory()->create();
    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = '123456';
    login($user);

    $response = $this->putJson(action([AdminUserController::class, 'update'], $userA->uuid), [
        'password' => $updated,
        'password_confirmation' => $updated,
    ]);

    $response->assertOk();

    $loginResponse = $this->postJson(action([LoginController::class]), [
        'email' => $userA->email,
        'password' => $updated,
    ]);

    $loginResponse->assertOk();
});
