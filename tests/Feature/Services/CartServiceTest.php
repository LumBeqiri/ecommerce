<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Models\Vendor;
use App\Services\CartService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
});

it('saves cookie items to cart', function ($status) {
    Currency::factory()->create();
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    $user = User::factory()->create();
    Vendor::factory()->create();
    Product::factory()->available()->create();

    $variant1 = Variant::factory()->create(['status' => Product::AVAILABLE_PRODUCT, 'stock' => 5, 'publish_status' => $status]);
    $variant2 = Variant::factory()->create(['status' => Product::AVAILABLE_PRODUCT, 'stock' => 5, 'publish_status' => $status]);
    $variant3 = Variant::factory()->create(['status' => Product::AVAILABLE_PRODUCT, 'stock' => 5, 'publish_status' => $status]);

    VariantPrice::factory()->for($variant1)->for($region)->create();
    VariantPrice::factory()->for($variant2)->for($region)->create();
    VariantPrice::factory()->for($variant3)->for($region)->create();

    $items = [
        ['variant_id' => $variant1->uuid, 'quantity' => 2],
        ['variant_id' => $variant2->uuid, 'quantity' => 1],
        ['variant_id' => $variant3->uuid, 'quantity' => 3],
    ];

    CartService::saveCookieItemsToCart($items, $user);

    $cart = Cart::where('user_id', $user->id)->where('region_id', $region->id)->firstOrFail();

    if ($status === Product::DRAFT) {
        expect($cart->cart_items()->count())->toBe(0);
    } else {
        expect($cart->cart_items()->count())->toBe(3);

        $cart_item1 = CartItem::where('cart_id', $cart->id)->where('variant_id', $variant1->id)->firstOrFail();
        expect($cart_item1->quantity)->toBe(2);

        $cart_item2 = CartItem::where('cart_id', $cart->id)->where('variant_id', $variant2->id)->firstOrFail();
        expect($cart_item2->quantity)->toBe(1);

        $cart_item3 = CartItem::where('cart_id', $cart->id)->where('variant_id', $variant3->id)->firstOrFail();
        expect($cart_item3->quantity)->toBe(3);
    }
})->with(
    [
        Product::PUBLISHED,
    ]
);
