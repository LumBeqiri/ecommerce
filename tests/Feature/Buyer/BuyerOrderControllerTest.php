<?php

namespace Tests\Feature\Buyer;

use App\Http\Controllers\Buyer\BuyerOrderController;
use App\Models\Buyer;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Models\Vendor;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

use function Pest\Faker\fake;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
});

it('can create an order with unchanged shipping address', function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    $region = Region::factory()->create(['id' => 1]);
    Country::factory()->for($region)->create();
    $buyerUser = User::factory()->create(['region_id' => $region->id]);
    $buyer = Buyer::factory()->create(['user_id' => $buyerUser->id]);

    $vendor = Vendor::factory()->create();
    $product = Product::factory()->available()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->available()->published()->create(['stock' => 50]);
    VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    $cart = Cart::factory()->for($buyer)->create();
    $cart_item = CartItem::factory()->for($cart)->create(['variant_id' => $variant->id]);

    login($buyerUser);

    $response = $this->postJson(action([BuyerOrderController::class, 'store']), [
        'different_shipping_address' => 0,
        'shipping_name' => fake()->name,
        'shipping_address' => fake()->streetAddress,
        'shipping_city' => fake()->city,
        'shipping_country' => fake()->country,
        'order_email' => fake()->email,
        'order_phone' => fake()->phoneNumber,
        'tax_rate' => 18,
        'ordered_at' => now(),
    ]);

    $response->assertOk();

    $order_ulid = $response->json('id');
    $order = Order::where('ulid', $order_ulid)->select('id')->first();

    $this->assertDatabaseHas(CartItem::class, ['id' => $cart_item->id, 'cart_id' => $buyer->cart->id]);
    $this->assertDatabaseHas(Cart::class, ['buyer_id' => $buyer->id]);
    $this->assertDatabaseHas(Order::class, ['buyer_id' => $buyer->id, 'ulid' => $order_ulid]);
    $this->assertDatabaseHas(OrderItem::class, ['order_id' => $order->id]);
});

it('can create an order with changed shipping address', function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    $region = Region::factory()->create(['id' => 1]);
    Country::factory()->for($region)->create();
    $buyerUser = User::factory()->create(['region_id' => $region->id]);
    $buyer = Buyer::factory()->create(['user_id' => $buyerUser->id]);

    $vendor = Vendor::factory()->create();
    $product = Product::factory()->available()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->available()->published()->create(['stock' => 50]);
    VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    $cart = Cart::factory()->for($buyer)->create();
    $cart_item = CartItem::factory()->for($cart)->create(['variant_id' => $variant->id]);

    login($buyerUser);

    $shipping_city = fake()->city;
    $response = $this->postJson(action([BuyerOrderController::class, 'store']), [
        'different_shipping_address' => 1,
        'shipping_name' => fake()->name,
        'shipping_address' => fake()->streetAddress,
        'shipping_city' => $shipping_city,
        'shipping_country' => fake()->country,
        'order_email' => fake()->email,
        'order_phone' => fake()->phoneNumber,
        'tax_rate' => 18,
        'ordered_at' => now(),
    ]);

    $response->assertOk();

    $order_ulid = $response->json('id');
    $order = Order::where('ulid', $order_ulid)->select('id')->first();

    $this->assertDatabaseHas(CartItem::class, ['id' => $cart_item->id, 'cart_id' => $buyer->cart->id]);
    $this->assertDatabaseHas(Cart::class, ['buyer_id' => $buyer->id]);
    $this->assertDatabaseHas(Order::class, ['buyer_id' => $buyer->id, 'ulid' => $order_ulid, 'shipping_city' => $shipping_city ]);
    $this->assertDatabaseHas(OrderItem::class, ['order_id' => $order->id]);
});
