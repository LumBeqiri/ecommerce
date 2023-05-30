<?php

namespace Tests\Feature\Buyer;

use App\Http\Controllers\Buyer\BuyerOrderController;
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
    $buyer = User::factory()->create();

    $buyer->country->region_id = 1;
    $buyer->save();

    Product::factory()->available()->create();
    Variant::factory()->available()->published()->create(['stock' => 50]);
    VariantPrice::factory()->create();

    $cart = Cart::factory()->for($buyer)->create();
    $cart_item = CartItem::factory()->for($cart)->create();

    login($buyer);

    $response = $this->postJson(action([BuyerOrderController::class, 'store']), [
        'different_shipping_address' => 0,
        'shipping_name' => fake()->name,
        'shipping_address' => fake()->streetAddress,
        'shipping_city' => fake()->city,
        'shipping_country' => fake()->country,
        'order_email' => fake()->email,
        'order_phone' => fake()->phoneNumber,
    ]);

    $response->assertOk();

    $order_uuid = $response->json('id');
    $order = Order::where('uuid', $order_uuid)->select('id')->first();

    $this->assertDatabaseHas(CartItem::class, ['id' => $cart_item->id, 'cart_id' => $buyer->cart->id]);
    $this->assertDatabaseHas(Cart::class, ['user_id' => $buyer->id]);
    $this->assertDatabaseHas(Order::class, ['buyer_id' => $buyer->id, 'uuid' => $order_uuid]);
    $this->assertDatabaseHas(OrderItem::class, ['order_id' => $order->id]);
});


it('can create an order with changed shipping address', function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    $region = Region::factory()->create(['id' => 1]);
    Country::factory()->for($region)->create();
    $buyer = User::factory()->create();

    $buyer->country->region_id = 1;
    $buyer->save();

    Product::factory()->available()->create();
    Variant::factory()->available()->published()->create(['stock' => 50]);
    VariantPrice::factory()->create();

    $cart = Cart::factory()->for($buyer)->create();
    $cart_item = CartItem::factory()->for($cart)->create();

    login($buyer);

    $response = $this->postJson(action([BuyerOrderController::class, 'store']), [
        'different_shipping_address' => 1,
        'shipping_name' => fake()->name,
        'shipping_address' => $buyer->shipping_address,
        'shipping_city' => $buyer->city,
        'shipping_country' => $buyer->country->name,
        'order_email' => $buyer->email,
        'order_phone' => $buyer->phone
    ]);

    $response->assertOk();

    $order_uuid = $response->json('id');
    $order = Order::where('uuid', $order_uuid)->select('id')->first();

    $this->assertDatabaseHas(CartItem::class, ['id' => $cart_item->id, 'cart_id' => $buyer->cart->id]);
    $this->assertDatabaseHas(Cart::class, ['user_id' => $buyer->id]);
    $this->assertDatabaseHas(Order::class, ['buyer_id' => $buyer->id, 'uuid' => $order_uuid, 'shipping_city' => $buyer->city]);
    $this->assertDatabaseHas(OrderItem::class, ['order_id' => $order->id]);
});
