<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Buyer;
use App\Models\Order;
use App\Models\Region;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Currency;
use App\Models\TaxProvider;
use App\Models\VendorOrder;
use App\values\OrderStatusTypes;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Database\Seeders\RoleAndPermissionSeeder;
use App\Http\Controllers\Admin\Order\AdminOrderController;

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
    $updatedCountry = Country::factory()->create();

    login($user);

    $response = $this->putJson(action([AdminOrderController::class, 'update'], $order->ulid), [
        'shipping_name' => $updatedName,
        'shipping_address' => $updatedAddress,
        'shipping_country_id' => $updatedCountry->id,
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas(Order::class, ['shipping_name' => $updatedName, 'shipping_address' => $updatedAddress,
        'shipping_country_id' => $updatedCountry->id, ]);
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

it('admin can delete order with related vendor orders', function () {
    $currency = Currency::factory()->create();
    $region = Region::factory()->create();
    $country = Country::factory()->for($region)->create();

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $order = Order::factory()->create([
        'currency_id' => $currency->id,
        'shipping_country_id' => $country->id,
    ]);

    // Create vendor orders
    $vendorOrders = VendorOrder::factory(2)->create([
        'order_id' => $order->id,
    ]);

    login($admin);

    $response = $this->deleteJson(action([AdminOrderController::class, 'destroy'], $order));

    $response->assertOk();

    // Check order was deleted
    $this->assertDatabaseMissing('orders', [
        'id' => $order->id,
    ]);

    // Check vendor orders were deleted
    foreach ($vendorOrders as $vendorOrder) {
        $this->assertDatabaseMissing('vendor_orders', [
            'id' => $vendorOrder->id,
        ]);
    }
});

it('validates order status update', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $order = Order::factory()->create();

    login($admin);

    $response = $this->putJson(
        action([AdminOrderController::class, 'update'], $order),
        ['status' => 'invalid_status']
    );

    $response->assertUnprocessable();
})->todo('admin should be able to update the order status in AdminOrderController');


it('admin can update order status', function () {
    $currency = Currency::factory()->create();
    $region = Region::factory()->create();
    $country = Country::factory()->for($region)->create();

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $order = Order::factory()->create([
        'currency_id' => $currency->id,
        'shipping_country_id' => $country->id,
        'status' => OrderStatusTypes::PENDING->value,
    ]);

    // Create vendor orders
    $vendorOrders = VendorOrder::factory(2)->create([
        'order_id' => $order->id,
        'status' => OrderStatusTypes::PENDING->value,
    ]);

    login($admin);

    $response = $this->putJson(
        action([AdminOrderController::class, 'update'], $order),
        ['status' => OrderStatusTypes::PROCESSING->value]
    );

    $response->assertOk();

    // Check main order status was updated
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => OrderStatusTypes::PROCESSING->value,
    ]);

    // Check all vendor orders were updated
    foreach ($vendorOrders as $vendorOrder) {
        $this->assertDatabaseHas('vendor_orders', [
            'id' => $vendorOrder->id,
            'status' => OrderStatusTypes::PROCESSING->value,
        ]);
    }
})->todo('admin should be able to update the order status in AdminOrderController');


it('admin can view all orders', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Order::factory(5)->create();
    login($admin);

    $response = $this->getJson(action([AdminOrderController::class, 'index']));

    $response->assertOk();
    expect(count($response->json()))->toBe(5);
});