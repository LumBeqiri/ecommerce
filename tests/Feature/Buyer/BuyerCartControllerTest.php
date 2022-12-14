<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use App\Models\CartItem;
use App\Http\Controllers\Buyer\BuyerCartController;
use App\Models\Cart;

it('can add an item to the cart', function(){
    $seller = User::factory()->create();
    $buyer = User::factory()->create();

    $product = Product::factory()->create(['seller_id' => $seller->id]);
    $variant = Variant::factory()->available()->for($product)->create(['stock' => 50]);
    $count = 2;

    login($buyer);

    $response = $this->postJson(action([BuyerCartController::class, 'add_to_cart']), [
        'variant_id' => $variant->uuid,
        'count' => $count
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(CartItem::class, ['variant_id' => $variant->id, 'count' => $count, 'cart_id' => $buyer->cart->id]);
    $this->assertDatabaseHas(Cart::class, ['user_id' => $buyer->id]);
});

it('can remove an item to the cart', function(){
    $seller = User::factory()->create();
    $buyer = User::factory()->create();

    $stock = 50;
    $inCart = 20;
    $toRemove = 10;
    $itemsLeft = $inCart - $toRemove;

    $product = Product::factory()->create(['seller_id' => $seller->id]);
    $variant = Variant::factory()->available()->for($product)->create(['stock' => $stock]);

    login($buyer);

    $cart = Cart::factory()->for($buyer)->create();
    CartItem::factory()->for($cart)->create(['variant_id' => $variant->id, 'count' => $inCart]);


    $response = $this->deleteJson(action([BuyerCartController::class, 'remove_from_cart']), [
        'variant_id' => $variant->uuid,
        'count' => $toRemove
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(CartItem::class, ['variant_id' => $variant->id, 'count' => $itemsLeft, 'cart_id' => $buyer->cart->id]);
    $this->assertDatabaseHas(Cart::class, ['user_id' => $buyer->id]);
});