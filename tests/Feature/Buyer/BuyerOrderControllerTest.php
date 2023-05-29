<?php

namespace Tests\Feature\Buyer;

use App\Http\Controllers\Buyer\BuyerCartController;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantPrice;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
});

it('can add an item to the cart', function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    $region = Region::factory()->create(['id' => 1]);
    $country = Country::factory()->for($region)->create();
    $seller = User::factory()->create(['country_id' => $country->id]);
    $buyer = User::factory()->create();

    $buyer->country->region_id = 1;
    $buyer->save();

    $product = Product::factory()->available()->create(['seller_id' => $seller->id]);
    $variant1 = Variant::factory()->available()->published()->for($product)->create(['stock' => 50]);
    VariantPrice::factory()->for($variant1)->create();
    $quantity = 2;

    login($buyer);

    $items_json = [
        [
            'variant_id' => $variant1->uuid,
            'quantity' => $quantity,
        ],
    ];

    $response = $this->postJson(action([BuyerCartController::class, 'store']), [
        'items' => $items_json,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(CartItem::class, ['variant_id' => $variant1->id, 'quantity' => $quantity, 'cart_id' => $buyer->cart->id]);
    $this->assertDatabaseHas(Cart::class, ['user_id' => $buyer->id]);
});
