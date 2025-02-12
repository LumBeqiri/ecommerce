<?php

use App\Models\Buyer;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantPrice;

beforeEach(function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    Notification::fake();
    Bus::fake();
});

it('can login user and sync cart items', function () {
    $region = Region::factory()->create();
    $currency = Currency::factory()->create();

    $user = User::factory()->create([
        'region_id' => $region->id,
        'password' => bcrypt('password'),
    ]);

    $buyer = Buyer::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create();
    $variant = Variant::factory()->available()->published()->create(['product_id' => $product->id]);

    VariantPrice::factory()->create([
        'variant_id' => $variant->id,
        'region_id' => $region->id,
        'price' => 1500, // price in cents
        'currency_id' => $currency->id,
    ]);

    $cartItemsPayload = [
        [
            'variant_id' => $variant->ulid,
            'quantity' => 2,
        ],
    ];

    $response = $this->postJson(route('login'), [
        'email' => $user->email,
        'password' => 'password',
        'cart_items' => $cartItemsPayload,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('carts', [
        'buyer_id' => $buyer->id,
        'is_closed' => false,
        'region_id' => $region->id,
    ]);

    $cart = Cart::where('buyer_id', $buyer->id)->first();
    $this->assertNotNull($cart);

    $this->assertDatabaseHas('cart_items', [
        'cart_id' => $cart->id,
        'variant_id' => $variant->id,
        'quantity' => 2,
        'price' => 1500,
        'currency_id' => $currency->id,
    ]);
});
