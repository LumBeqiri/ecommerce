<?php

use App\Models\Cart;
use App\Models\User;
use App\Models\Region;
use App\Models\Seller;
use App\Models\Country;
use App\Models\Product;
use App\Models\Variant;
use App\Models\CartItem;
use App\Models\TaxProvider;
use Illuminate\Support\Facades\Bus;
use Database\Seeders\CurrencySeeder;
use Illuminate\Support\Facades\Notification;
use Database\Seeders\RoleAndPermissionSeeder;
use App\Http\Controllers\Admin\Cart\AdminCartController;

beforeEach(function () {
    Notification::fake();
    Bus::fake();
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);
});

it('admin can show carts', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $user = User::factory()->create();

    Cart::factory()->count(1)->create();

    $user->assignRole('admin');

    Product::factory()->create();

    login($user);

    $response = $this->getJson(action([AdminCartController::class, 'index']));

    $response->assertOk();
});

it('admin can show carts with items', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    User::factory()->count(1)->create();
    Product::factory()->count(5)->create();
    Variant::factory()->count(5)->create();
    Cart::factory()->count(1)->create();

    CartItem::factory()->count(5)->create();
    $user = User::factory()->create();
    $user->assignRole('admin');

    Product::factory()->create();

    login($user);

    $response = $this->getJson(action([AdminCartController::class, 'index'], ['include' => 'cart_items']));

    $response->assertOk();
});

it('admin can show carts with user', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    User::factory()->count(1)->create();
    Product::factory()->count(5)->create();
    Variant::factory()->count(5)->create();
    Cart::factory()->count(1)->create();

    CartItem::factory()->count(5)->create();

    $user = User::factory()->create();
    $user->assignRole('admin');

    Product::factory()->create();

    login($user);

    $response = $this->getJson('admin/carts?include=user');

    $response->assertOk();
});

it('admin can show cart with user', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    User::factory()->count(1)->create();
    Product::factory()->count(5)->create();
    Variant::factory()->count(5)->create();
    Cart::factory()->count(1)->create();
    CartItem::factory()->count(5)->create();

    $user = User::factory()->create();
    $user->assignRole('admin');

    Product::factory()->create();

    login($user);

    $response = $this->getJson('admin/carts?include=user', ['cart' => Cart::inRandomOrder()->first()->uuid]);

    $response->assertOk();
});

it('admin can show cart with cart items', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $seller = Seller::factory()->create();
    $product = Product::factory()->for($seller)->create();
    $variant = Variant::factory()->for($product)->create();
    $cart = Cart::factory()->for(User::factory())->create();
    CartItem::factory()->for($cart)->for($variant)->create();
    $user = User::factory()->create();
    $user->assignRole('admin');

    Product::factory()->create();

    login($user);

    $response = $this->getJson('admin/carts?include=cart_items', ['cart' => $cart]);

    $response->assertOk();
});

it('admin can delete cart', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    $user = User::factory()->create();
    $cart = Cart::factory()->for($user)->create();

    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson(action([AdminCartController::class, 'destroy'], $cart->uuid));

    $response->assertOk();

    $this->assertDatabaseMissing(Cart::class, ['id' => $cart->id]);
});

it('admin can remove item qty from cart', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $initialAmount = 8;
    $amountToBeRemoved = 4;
    $amountLeft = $initialAmount - $amountToBeRemoved;

    $seller = Seller::factory()->create();
    $product = Product::factory()->for($seller)->create();
    $variant = Variant::factory()->for($product)->create();
    $cart = Cart::factory()->for(User::factory())->create();
    $cart_item = CartItem::factory()->create([
        'cart_id' => $cart->id,
        'variant_id' => $variant->id,
        'quantity' => $initialAmount,
    ]);

    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson('admin/carts/'.$cart->uuid.'/variants/'.$variant->uuid, [
        'quantity' => $amountToBeRemoved,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(CartItem::class, ['id' => $cart_item->id, 'quantity' => $amountLeft]);
});

it('admin can remove item from cart completely', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $initialAmount = 8;
    $seller = Seller::factory()->create();
    $product = Product::factory()->for($seller)->create();
    $variant = Variant::factory()->for($product)->create();
    $cart = Cart::factory()->for(User::factory())->create();
    $cart_item = CartItem::factory()->create([
        'cart_id' => $cart->id,
        'variant_id' => $variant->id,
        'quantity' => $initialAmount,
    ]);

    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson('admin/carts/'.$cart->uuid.'/variants/'.$variant->uuid);

    $response->assertOk();

    $this->assertDatabaseMissing(CartItem::class, ['id' => $cart_item->id]);
});
