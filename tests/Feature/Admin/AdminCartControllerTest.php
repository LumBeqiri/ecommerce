<?php

use App\Http\Controllers\Admin\Cart\AdminCartController;
use App\Models\Buyer;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
use App\Models\Vendor;
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

it('admin can show carts', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $user = User::factory()->create();

    Buyer::factory()->create();

    Cart::factory()->count(1)->create();

    $user->assignRole('admin');
    Vendor::factory()->create();
    Product::factory()->create();

    login($user);

    $response = $this->getJson(action([AdminCartController::class, 'index']));

    $response->assertOk();
});

it('admin can show carts with items', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    Vendor::factory()->create();

    User::factory()->count(1)->create();
    Product::factory()->count(5)->create();
    Variant::factory()->count(5)->create();
    Buyer::factory()->create();
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
    Vendor::factory()->create();
    User::factory()->create();
    Product::factory()->count(5)->create();
    Variant::factory()->count(5)->create();
    Buyer::factory()->create();
    Cart::factory()->count(1)->create();
    CartItem::factory()->count(5)->create();

    $user = User::factory()->create();
    $user->assignRole('admin');

    Product::factory()->create();

    login($user);

    $response = $this->getJson('admin/carts?include=buyer');

    $response->assertOk();
});

it('admin can show cart with user', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    Vendor::factory()->create();

    User::factory()->create();
    Buyer::factory()->create();
    Product::factory()->count(5)->create();
    Variant::factory()->count(5)->create();
    Cart::factory()->create();
    CartItem::factory()->count(5)->create();

    $user = User::factory()->create();
    $user->assignRole('admin');

    Product::factory()->create();

    login($user);

    $response = $this->getJson('admin/carts?include=buyer', ['cart' => Cart::inRandomOrder()->first()->ulid]);

    $response->assertOk();
});

it('admin can show cart with cart items', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    Vendor::factory()->create();
    User::factory()->create();
    Buyer::factory()->create();
    $product = Product::factory()->create();
    $variant = Variant::factory()->for($product)->create();
    $cart = Cart::factory()->for(Buyer::factory())->create();
    CartItem::factory()->for($cart)->for($variant)->create();
    $user = User::factory()->create();
    $user->assignRole('admin');
    Product::factory()->create([
        'vendor_id' => Vendor::factory()->create()->id,
    ]);

    login($user);

    $response = $this->getJson('admin/carts?include=cart_items', ['cart' => $cart]);

    $response->assertOk();
});

it('admin can update cart', function () {
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

    $response = $this->putJson(action([AdminCartController::class, 'update'], $cart->ulid), [
        'is_closed' => true,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Cart::class, ['id' => $cart->id, 'is_closed' => true]);
});

it('admin can delete cart', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    $buyer = Buyer::factory()->create();
    $cart = Cart::factory()->for($buyer)->create();

    $user = User::factory()->create();
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson(action([AdminCartController::class, 'destroy'], $cart->ulid));

    $response->assertOk();

    $this->assertDatabaseMissing(Cart::class, ['id' => $cart->id]);
});

it('admin can remove item qty from cart', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    $initialAmount = 8;
    $amountToBeRemoved = 4;
    $amountLeft = $initialAmount - $amountToBeRemoved;

    $vendor = Vendor::factory()->create();
    Vendor::factory()->create();
    $product = Product::factory()->for($vendor)->create();
    $variant = Variant::factory()->for($product)->create();
    $cart = Cart::factory()->create([
        'buyer_id' => Buyer::factory()->create()->id,
    ]);
    $cart_item = CartItem::factory()->create([
        'cart_id' => $cart->id,
        'variant_id' => $variant->id,
        'quantity' => $initialAmount,
    ]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson('admin/carts/'.$cart->ulid.'/variants/'.$variant->ulid, [
        'quantity' => $amountToBeRemoved,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(CartItem::class, ['id' => $cart_item->id, 'quantity' => $amountLeft]);
});

it('admin can remove item from cart completely', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    $initialAmount = 8;
    $vendor = Vendor::factory()->create();
    $product = Product::factory()->for($vendor)->create();
    $variant = Variant::factory()->for($product)->create();
    $cart = Cart::factory()->create([
        'buyer_id' => Buyer::factory()->create()->id,
    ]);
    $cart_item = CartItem::factory()->create([
        'cart_id' => $cart->id,
        'variant_id' => $variant->id,
        'quantity' => $initialAmount,
    ]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson('admin/carts/'.$cart->ulid.'/variants/'.$variant->ulid);

    $response->assertOk();

    $this->assertDatabaseMissing(CartItem::class, ['id' => $cart_item->id]);
});
