<?php

use App\Http\Controllers\Buyer\CheckoutController;
use App\Models\Buyer;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Models\Vendor;
use App\Models\VendorOrder;
use App\values\OrderStatusTypes;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
});

it('can create an order from cart', function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    $country = Country::factory()->for($region)->create();

    $buyerUser = User::factory()->create(['region_id' => $region->id]);
    $buyer = Buyer::factory()->create(['user_id' => $buyerUser->id]);

    $vendor = Vendor::factory()->create();
    $product = Product::factory()->available()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()
        ->available()
        ->published()
        ->for($product)
        ->create(['stock' => 50]);

    VariantPrice::factory()->create([
        'variant_id' => $variant->id,
        'region_id' => $region->id,
        'price' => 1500, // price in cents
    ]);

    $cart = Cart::factory()->create([
        'buyer_id' => $buyer->id,
        'region_id' => $region->id,
        'total_cart_price' => 3000,
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'variant_id' => $variant->id,
        'quantity' => 2,
        'price' => 1500,
    ]);

    login($buyerUser);

    $orderData = [
        'different_shipping_address' => 1,
        'shipping_name' => 'John Doe',
        'shipping_address' => '123 Test St',
        'shipping_city' => 'Test City',
        'shipping_country_id' => $country->id,
        'order_email' => 'test@example.com',
        'order_phone' => '1234567890',
        'tax_rate' => 10,
        'ordered_at' => now()->toDateTimeString(),
    ];

    $response = $this->postJson(
        action([CheckoutController::class, 'store']),
        $orderData
    );

    $response->assertOk();

    $order = Order::first();

    $this->assertDatabaseHas('orders', [
        'buyer_id' => $buyer->id,
        'shipping_name' => 'John Doe',
        'shipping_address' => '123 Test St',
        'shipping_city' => 'Test City',
        'shipping_country_id' => $country->id,
        'total' => 3000,
        'tax_rate' => 10,
    ]);

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'variant_id' => $variant->id,
        'quantity' => 2,
        'price' => 1500,
    ]);

    // Check vendor order was created
    $this->assertDatabaseHas('vendor_orders', [
        'vendor_id' => $vendor->id,
        'order_id' => $order->id,
        'total' => 3000,
        'status' => OrderStatusTypes::PENDING->value,
    ]);

    // Check order items are linked to vendor order
    $vendorOrder = VendorOrder::where('order_id', $order->id)->first();
    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'variant_id' => $variant->id,
        'quantity' => 2,
        'price' => 1500,
    ]);
});

it('can create an order with default shipping address', function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    $country = Country::factory()->for($region)->create();

    $buyerUser = User::factory()->create(['region_id' => $region->id]);
    $buyer = Buyer::factory()->create([
        'user_id' => $buyerUser->id,
        'shipping_address' => 'Default Address',
    ]);

    $vendor = Vendor::factory()->create();
    $product = Product::factory()->available()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->available()->published()->create(['stock' => 50]);

    VariantPrice::factory()->create([
        'variant_id' => $variant->id,
        'region_id' => $region->id,
        'price' => 1500,
    ]);

    $cart = Cart::factory()->create([
        'buyer_id' => $buyer->id,
        'region_id' => $region->id,
        'total_cart_price' => 3000,
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'variant_id' => $variant->id,
        'quantity' => 2,
        'price' => 1500,
    ]);

    login($buyerUser);

    $orderData = [
        'shipping_city' => 'Test City',
        'shipping_country_id' => $country->id,
        'different_shipping_address' => 0,
        'order_email' => 'test@example.com',
        'order_phone' => '1234567890',
        'tax_rate' => 10,
        'ordered_at' => now()->toDateTimeString(),
    ];

    $response = $this->postJson(
        action([CheckoutController::class, 'store']),
        $orderData
    );

    $response->assertOk();

    $this->assertDatabaseHas('orders', [
        'buyer_id' => $buyer->id,
        'shipping_address' => 'Default Address',
        'total' => 3000,
        'tax_rate' => 10,
    ]);
});
