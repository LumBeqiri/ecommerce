<?php

use App\Http\Controllers\Admin\Users\AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

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
        'city' => $this->faker()->city(),
        'country_id' => Country::inRandomOrder()->first()->id,
        'zip' => $this->faker()->numberBetween(10000, 100000),
        'phone' => $this->faker()->phoneNumber(),
        'email' => $this->faker()->email(),
        'shipping_address' => $this->faker()->streetAddress(),
        'password' => $password,
        'password_confirmation' => $password,
    ]);

    $response->assertStatus(200);

    $user_id = $response->json('id');

    $this->assertDatabaseHas(User::class, ['uuid' => $user_id]);
});

it('admin can update user name', function () {
    $userA = User::factory()->create(['name' => 'John']);
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updated = 'new name';
    login($user);

    $response = $this->putJson(action([AdminUserController::class, 'update'], $userA->uuid), [
        'name' => $updated,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(User::class, ['name' => $updated]);
});

it('admin can update user city', function () {
    $userA = User::factory()->create(['city' => 'Amber']);
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updated = 'Tirana';
    login($user);

    $response = $this->putJson(action([AdminUserController::class, 'update'], $userA->uuid), [
        'city' => $updated,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(User::class, ['city' => $updated]);
});

it('admin can update user country', function () {
    $old_country = Country::factory()->create();
    $userA = User::factory()->create(['country_id' => $old_country->id]);
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $new_country = Country::factory()->create();

    login($user);

    $response = $this->putJson(action([AdminUserController::class, 'update'], $userA->uuid), [
        'country_id' => $new_country->id,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(User::class, ['country_id' => $new_country->id]);
});

it('admin can update user phone', function () {
    $userA = User::factory()->create(['phone' => '03212']);
    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updated = '123456';
    login($user);

    $response = $this->putJson(action([AdminUserController::class, 'update'], $userA->uuid), [
        'phone' => $updated,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(User::class, ['phone' => $updated]);
});

it('admin can change user password', function () {
    $userA = User::factory()->create();
    $user = User::factory()->create(['name' => 'Lum']);
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
