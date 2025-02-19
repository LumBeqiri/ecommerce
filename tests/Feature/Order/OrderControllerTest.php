<?php

use App\Models\User;
use App\Models\Buyer;
use App\Models\Order;
use App\Models\Region;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Currency;
use App\Models\OrderItem;
use App\Models\TaxProvider;
use App\Models\VendorOrder;
use App\values\OrderStatusTypes;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Database\Seeders\RoleAndPermissionSeeder;
use App\Http\Controllers\User\Order\OrderController;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
    $this->seed(RoleAndPermissionSeeder::class);
    TaxProvider::factory()->create();

});

it('buyer can view their own orders', function () {
    $currency = Currency::factory()->create();
    $region = Region::factory()->create();
    $country = Country::factory()->for($region)->create();

    $buyerUser = User::factory()->create(['region_id' => $region->id]);
    $buyerUser->assignRole('buyer');
    $buyer = Buyer::factory()->create(['user_id' => $buyerUser->id]);

    // Create orders for the buyer
    $orders = Order::factory(3)->create([
        'buyer_id' => $buyer->id,
        'currency_id' => $currency->id,
        'shipping_country_id' => $country->id,
    ]);

    foreach ($orders as $order) {
        $variant = Variant::factory()->create();
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'variant_id' => $variant->id,
        ]);
    }

    // Create order for different buyer
    $otherBuyer = Buyer::factory()->create();
    Order::factory()->create([
        'buyer_id' => $otherBuyer->id,
        'currency_id' => $currency->id,
        'shipping_country_id' => $country->id,
    ]);

    login($buyerUser);

    $response = $this->getJson(action([OrderController::class, 'index']));

    $response->assertOk();

});

it('vendor can view orders containing their products', function () {
    $currency = Currency::factory()->create();
    $region = Region::factory()->create();
    $country = Country::factory()->for($region)->create();

    $vendorUser = User::factory()->create();
    $vendorUser->assignRole('vendor');
    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);

    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $order = Order::factory()->create([
        'currency_id' => $currency->id,
        'shipping_country_id' => $country->id,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'variant_id' => $variant->id,
    ]);

    login($vendorUser);

    $response = $this->getJson(action([OrderController::class, 'show'], $order));

    $response->assertOk();
});

it('vendor cannot view orders without their products', function () {
    $currency = Currency::factory()->create();
    $region = Region::factory()->create();
    $country = Country::factory()->for($region)->create();

    $vendorUser = User::factory()->create();
    $vendorUser->assignRole('vendor');
    Vendor::factory()->create(['user_id' => $vendorUser->id]);

    $otherVendor = Vendor::factory()->create();
    $product = Product::factory()->create(['vendor_id' => $otherVendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $order = Order::factory()->create([
        'currency_id' => $currency->id,
        'shipping_country_id' => $country->id,
    ]);

    OrderItem::factory()->create([
        'order_id' => $order->id,
        'variant_id' => $variant->id,
    ]);

    login($vendorUser);

    $response = $this->getJson(action([OrderController::class, 'show'], $order));

    $response->assertForbidden();
});

it('admin can view all orders', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Order::factory(5)->create();
    login($admin);

    $response = $this->getJson(action([OrderController::class, 'index']));

    $response->assertOk();
    expect(count($response->json()))->toBe(5);
});

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
        action([OrderController::class, 'update'], $order),
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
});

it('non-admin cannot update order status', function () {
    $buyer = User::factory()->create();
    $buyer->assignRole('buyer');

    $order = Order::factory()->create();

    login($buyer);

    $response = $this->putJson(
        action([OrderController::class, 'update'], $order),
        ['status' => OrderStatusTypes::PROCESSING->value]
    );

    $response->assertForbidden();
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

    $response = $this->deleteJson(action([OrderController::class, 'destroy'], $order));

    $response->assertOk();
    $response->assertJson([
        'data' => 'Order deleted successfully',
    ]);

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

it('non-admin cannot delete order', function () {
    $buyer = User::factory()->create();
    $buyer->assignRole('buyer');

    $order = Order::factory()->create();
    VendorOrder::factory()->create(['order_id' => $order->id]);

    login($buyer);

    $response = $this->deleteJson(action([OrderController::class, 'destroy'], $order));

    $response->assertForbidden();

    // Verify nothing was deleted
    $this->assertDatabaseHas('orders', ['id' => $order->id]);
    $this->assertDatabaseHas('vendor_orders', ['order_id' => $order->id]);
});

it('validates order status update', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $order = Order::factory()->create();

    login($admin);

    $response = $this->putJson(
        action([OrderController::class, 'update'], $order),
        ['status' => 'invalid_status']
    );

    $response->assertUnprocessable();
});
