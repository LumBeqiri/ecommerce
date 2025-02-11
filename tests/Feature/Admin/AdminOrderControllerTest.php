<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\Order\AdminOrderController;
use App\Models\Buyer;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    User::flushEventListeners();

    Currency::factory()->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    Payment::factory()->create();
    $this->seed(RoleAndPermissionSeeder::class);
    Notification::fake();
    Bus::fake();
});

test('admin can update order', function () {
    $user = User::factory()->create(['email' => 'lumadmin@example.com']);
    $buyer = Buyer::factory()->create();
    $order = Order::factory()->for($buyer)->create();

    $user->assignRole('admin');
    $updatedName = 'new name';
    $updatedAddress = 'new address';
    $updatedCountry = 'new country';

    login($user);

    $response = $this->putJson(action([AdminOrderController::class, 'update'], $order->ulid), [
        'shipping_name' => $updatedName,
        'shipping_address' => $updatedAddress,
        'shipping_country' => $updatedCountry,
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas(Order::class, ['shipping_name' => $updatedName, 'shipping_address' => $updatedAddress,
        'shipping_country' => $updatedCountry, ]);
});

test('admin can delete order', function () {
    User::factory()->create();
    $buyer = Buyer::factory()->create();
    $user = User::factory()->create(['email' => 'lum@test.com']);
    $order = Order::factory()->for($buyer)->create();

    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson(action([AdminOrderController::class, 'destroy'], $order->ulid));

    $response->assertStatus(200);

    $this->assertDatabaseMissing(Order::class, ['id' => $order->id]);
});
