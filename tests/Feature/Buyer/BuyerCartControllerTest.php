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

    login($buyer);

    $product = Product::factory()->create(['seller_id' => $seller->id]);
    $variant = Variant::factory()->available()->for($product)->create();
    $count = 2;

    $response = $this->postJson(action([BuyerCartController::class, 'add_to_cart']), [
        'variant_id' => $variant->uuid,
        'count' => $count
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(CartItem::class, ['variant_id' => $variant->id, 'count' => $count, 'cart_id' => $buyer->cart->id]);
    $this->assertDatabaseHas(Cart::class, ['user_id' => $buyer->id]);
});