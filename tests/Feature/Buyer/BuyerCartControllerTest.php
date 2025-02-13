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

    TaxProvider::factory()->create();

    Currency::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
});

it('can add an item to the cart', function () {
    $seller = User::factory()->create();
    $region = Region::factory()->create();
    $buyerUser = User::factory()->create(['region_id' => $region->id]);
    $buyer = Buyer::factory()->create(['user_id' => $buyerUser->id]);

    $vendor = Vendor::factory()->for($seller)->create();
    $product = Product::factory()->available()->create(['vendor_id' => $vendor->id]);
    $variant1 = Variant::factory()->available()->published()->for($product)->create(['stock' => 50]);
    VariantPrice::factory()->create(['variant_id' => $variant1->id, 'region_id' => $region->id]);
    $quantity = 2;

    login($buyerUser);

    $items_json = [
        [
            'variant_id' => $variant1->ulid,
            'quantity' => $quantity,
        ],
    ];

    $response = $this->postJson(action([BuyerCartController::class, 'add_to_cart']), [
        'items' => $items_json,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(CartItem::class, ['variant_id' => $variant1->id, 'quantity' => $quantity, 'cart_id' => $buyer->cart->id]);
    $this->assertDatabaseHas(Cart::class, ['buyer_id' => $buyer->id]);
});

it('can remove an item from the cart', function () {
    $region = Region::factory()->create();
    $buyerUser = User::factory()->create(['region_id' => $region->id]);
    $buyer = Buyer::factory()->create(['user_id' => $buyerUser->id]);

    $vendor = Vendor::factory()->create();
    $product = Product::factory()->for($vendor)->available()->create();
    $variant = Variant::factory()->available()->for($product)->create(['stock' => 50]);

    VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    login($buyerUser);

    $cart = Cart::factory()->for($buyer)->create(['region_id' => $region->id]);
    $inCart = 20;
    $toRemove = 10;
    $itemsLeft = $inCart - $toRemove;

    CartItem::factory()->for($cart)->create(['variant_id' => $variant->id, 'quantity' => $inCart]);

    $response = $this->postJson(action([BuyerCartController::class, 'remove_from_cart']), [
        'variant_id' => $variant->ulid,
        'quantity' => $toRemove,
    ]);
    $response->assertOk();

    $this->assertDatabaseHas(CartItem::class, ['variant_id' => $variant->id, 'quantity' => $itemsLeft, 'cart_id' => $buyer->cart->id]);
    $this->assertDatabaseHas(Cart::class, ['buyer_id' => $buyer->id]);
});

it('can apply discount', function () {
    $currency = Currency::factory()->create(['has_cents' => true]);
    TaxProvider::factory()->create();
    $region = Region::factory()->create(['currency_id' => $currency->id]);
    $country = Country::factory()->for($region)->create();

    $buyerUser = User::factory()->create(['region_id' => $region->id]);
    $buyer = Buyer::factory()->create(['user_id' => $buyerUser->id]);

    $vendor = Vendor::factory()->create();
    $product = Product::factory()->available()->create(['vendor_id' => $vendor->id]);
    $variant1 = Variant::factory()->available()->for($product)->create(['stock' => 30]);
    $variant2 = Variant::factory()->available()->for($product)->create(['stock' => 30]);

    VariantPrice::factory()->create([
        'variant_id' => $variant1->id,
        'region_id' => $region->id,
        'price' => 10000, 
    ]);

    VariantPrice::factory()->create([
        'variant_id' => $variant2->id,
        'region_id' => $region->id,
        'price' => 20000,
    ]);

    $cart = Cart::factory()->create([
        'buyer_id' => $buyer->id,
        'total_cart_price' => 30000, 
        'has_been_discounted' => false,
        'region_id' => $region->id,
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'variant_id' => $variant1->id,
        'quantity' => 1,
        'price' => 10000,
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'variant_id' => $variant2->id,
        'quantity' => 1,
        'price' => 20000,
    ]);

    $discount_rule = DiscountRule::factory()->create([
        'region_id' => $region->id,
        'currency_id' => $currency->id,
        'discount_type' => DiscountRuleTypes::FIXED_AMOUNT,
        'value' => 10000, // $100.00
        'allocation' => DiscountAllocationTypes::TOTAL_AMOUNT,
    ]);

    $discount = Discount::factory()->create([
        'code' => 'OCTOBER',
        'is_disabled' => false,
        'discount_rule_id' => $discount_rule->id,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDay(),
    ]);

    login($buyerUser);

    $this->assertEquals(30000, $cart->total_cart_price);

    $response = $this->postJson(action([BuyerCartController::class, 'apply_discount']), [
        'code' => $discount->code,
    ]);

    $response->assertOk();

    $cart->refresh();
    $this->assertEquals(20000, $cart->total_cart_price);

    $this->assertDatabaseHas(Cart::class, [
        'id' => $cart->id,
        'total_cart_price' => 20000,
        'has_been_discounted' => true,
    ]);
});

it('can apply discount with non-cents currency', function () {
    $currency = Currency::factory()->create(['has_cents' => false]);
    TaxProvider::factory()->create();
    $region = Region::factory()->create(['currency_id' => $currency->id]);
    Country::factory()->for($region)->create();

    $buyerUser = User::factory()->create(['region_id' => $region->id]);
    $buyer = Buyer::factory()->create(['user_id' => $buyerUser->id]);

    $vendor = Vendor::factory()->create();
    $product = Product::factory()->available()->create(['vendor_id' => $vendor->id]);
    $variant1 = Variant::factory()->available()->for($product)->create(['stock' => 30]);

    VariantPrice::factory()->create([
        'variant_id' => $variant1->id,
        'region_id' => $region->id,
        'price' => 30000, 
    ]);


    $cart = Cart::factory()->create([
        'buyer_id' => $buyer->id,
        'total_cart_price' => 30000,
        'has_been_discounted' => false,
        'region_id' => $region->id,
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'variant_id' => $variant1->id,
        'quantity' => 1,
        'price' => 30000,
    ]);

    $discount_rule = DiscountRule::factory()->create([
        'region_id' => $region->id,
        'currency_id' => $currency->id,
        'discount_type' => DiscountRuleTypes::FIXED_AMOUNT,
        'value' => 100, 
        'allocation' => DiscountAllocationTypes::TOTAL_AMOUNT,
    ]);

    $discount = Discount::factory()->create([
        'code' => 'OCTOBER',
        'is_disabled' => false,
        'discount_rule_id' => $discount_rule->id,
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDay(),
    ]);

    login($buyerUser);

    $this->assertEquals(30000, $cart->total_cart_price);

    $response = $this->postJson(action([BuyerCartController::class, 'apply_discount']), [
        'code' => $discount->code,
    ]);

    $response->assertOk();

    $cart->refresh();
    $this->assertEquals(20000, $cart->total_cart_price); 
});
