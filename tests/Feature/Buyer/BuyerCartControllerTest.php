<?php

use App\Http\Controllers\Buyer\BuyerCartController;
use App\Models\Buyer;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Discount;
use App\Models\DiscountRule;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Models\Vendor;
use App\values\DiscountAllocationTypes;
use App\values\DiscountRuleTypes;
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
    $seller = User::factory()->create();
    $buyer = Buyer::factory()->create();

    $buyer->country->region_id = 1;
    $buyer->save();

    $vendor = Vendor::factory()->for($seller)->create();
    $product = Product::factory()->available()->create(['vendor_id' => $vendor->id]);
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
    $this->assertDatabaseHas(Cart::class, ['buyer_id' => $buyer->id]);
})->todo();

it('can remove an item from the cart', function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    User::factory()->create();
    $buyer = Buyer::factory()->create();

    $stock = 50;
    $inCart = 20;
    $toRemove = 10;
    $itemsLeft = $inCart - $toRemove;

    $vendor = Vendor::factory()->create();
    $product = Product::factory()->for($vendor)->available()->create();
    $variant = Variant::factory()->available()->for($product)->create(['stock' => $stock]);

    login($buyer);

    $cart = Cart::factory()->for($buyer)->create();
    CartItem::factory()->for($cart)->create(['variant_id' => $variant->id, 'quantity' => $inCart]);

    $response = $this->deleteJson(action([BuyerCartController::class, 'remove_from_cart']), [
        'variant_id' => $variant->uuid,
        'quantity' => $toRemove,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(CartItem::class, ['variant_id' => $variant->id, 'quantity' => $itemsLeft, 'cart_id' => $buyer->cart->id]);
    $this->assertDatabaseHas(Cart::class, ['buyer_id' => $buyer->id]);
})->todo();

it('can apply discount', function () {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    $country = Country::factory()->for($region)->create();

    $buyer = User::factory()->create(['country_id' => $country->id]);

    $product = Product::factory()->available()->create();
    $variant1 = Variant::factory()->available()->for($product)->create(['stock' => 30]);
    $variant2 = Variant::factory()->available()->for($product)->create(['stock' => 30]);

    VariantPrice::factory()->create([
        'variant_id' => $variant1->id,
        'region_id' => $region->id,
        'price' => 100,
    ]);

    VariantPrice::factory()->create([
        'variant_id' => $variant2->id,
        'region_id' => $region->id,
        'price' => 200,
    ]);

    $cart = Cart::factory()->create(['user_id' => $buyer->id, 'total_cart_price' => 300, 'has_been_discounted' => false, 'region_id' => $region->id]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'variant_id' => $variant1->id,
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'variant_id' => $variant2->id,
    ]);

    $discount_rule = DiscountRule::factory()->create([
        'region_id' => $region->id,
        'discount_type' => DiscountRuleTypes::FIXED_AMOUNT,
        'value' => 100,
        'allocation' => DiscountAllocationTypes::TOTAL_AMOUNT,
    ]);

    Discount::factory()->create([
        'code' => 'lum',
        'is_disabled' => false,
        'discount_rule_id' => $discount_rule->id,
    ]);

    login($buyer);

    $response = $this->postJson(action([BuyerCartController::class, 'apply_discount']), [
        'code' => 'lum',
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Cart::class, ['total_cart_price' => 200]);
})->todo();
