<?php

use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\CartItem\CartItemController;
use App\Models\Buyer;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Models\Vendor;
use App\values\Roles;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);
});

test('admin can show carts', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $user = User::factory()->create();

    Buyer::factory()->create();

    Cart::factory()->count(1)->create();

    $user->assignRole(Roles::ADMIN);

    Vendor::factory()->create();
    Product::factory()->create();

    login($user);

    $response = $this->getJson(action([CartController::class, 'index']));

    $response->assertOk();
});

test('admin can update cart', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $vendor = Vendor::factory()->create();
    User::factory()->create();
    Buyer::factory()->create();
    $product = Product::factory()->for($vendor)->create();
    $variant = Variant::factory()->for($product)->create();
    $cart = Cart::factory(['is_closed' => false])->for(Buyer::factory())->create();
    CartItem::factory()->for($cart)->for($variant)->create();
    $user = User::factory()->create();
    $user->assignRole('admin');

    Product::factory()->create();

    login($user);

    $response = $this->putJson(action([CartController::class, 'update'], $cart->ulid), [
        'is_closed' => true,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Cart::class, ['id' => $cart->id, 'is_closed' => true]);
});

test('admin can delete cart', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    $buyer = Buyer::factory()->create();
    $cart = Cart::factory()->for($buyer)->create();

    $user = User::factory()->create();
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson(action([CartController::class, 'destroy'], $cart->ulid));

    $response->assertOk();

    $this->assertDatabaseMissing(Cart::class, ['id' => $cart->id]);
});

test('admin can remove item qty from cart', function () {
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    $initialAmount = 8;
    $amountToBeRemoved = 4;
    $amountLeft = $initialAmount - $amountToBeRemoved;

    $vendor = Vendor::factory()->create();
    Vendor::factory()->create();
    $product = Product::factory()->for($vendor)->create();
    $variant = Variant::factory()->for($product)->create();
    VariantPrice::factory()->for($variant)->create(['region_id' => $region->id]);

    $cart = Cart::factory()->create([
        'buyer_id' => Buyer::factory()->create()->id,
        'region_id' => $region->id,
    ]);
    $cart_item = CartItem::factory()->create([
        'cart_id' => $cart->id,
        'variant_id' => $variant->id,
        'quantity' => $initialAmount,
    ]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    login($user);

    $response = $this->putJson(action([CartItemController::class, 'remove_from_cart'], [
        'cart' => $cart->ulid,
    ]), [
        'quantity' => $amountToBeRemoved,
        'variant_id' => $variant->ulid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(CartItem::class, ['id' => $cart_item->id, 'quantity' => $amountLeft]);
});
