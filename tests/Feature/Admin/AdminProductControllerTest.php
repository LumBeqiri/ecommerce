<?php

use App\Http\Controllers\Admin\Product\AdminProductController;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
});

it('admin can show products', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    $user = User::factory()->create();
    $user->assignRole('admin');

    Product::factory()->create();

    login($user);

    $response = $this->getJson(action([AdminProductController::class, 'index']));

    $response->assertOk();
});

it('admin can update product name', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    $product = Product::factory()->create();

    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updatedName = 'new name';
    login($user);

    $response = $this->putJson(action([AdminProductController::class, 'update'], $product->uuid), [
        'product_name' => $updatedName,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Product::class, ['product_name' => $updatedName]);
});

it('admin can update product long description', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    $product = Product::factory()->create();

    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updatedDescription = 'new description';
    login($user);

    $response = $this->putJson(action([AdminProductController::class, 'update'], $product->uuid), [
        'product_long_description' => $updatedDescription,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Product::class, ['product_long_description' => $updatedDescription]);
});

it('admin can update product short description', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    $product = Product::factory()->create();

    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updatedDescription = 'new description';
    login($user);

    $response = $this->putJson(action([AdminProductController::class, 'update'], $product->uuid), [
        'product_short_description' => $updatedDescription,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Product::class, ['product_short_description' => $updatedDescription]);
});

it('admin can update product status', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    $product = Product::factory()->unavailable()->create();

    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');
    $updatedStatus = Product::AVAILABLE_PRODUCT;
    login($user);

    $response = $this->putJson(action([AdminProductController::class, 'update'], $product->uuid), [
        'status' => $updatedStatus,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Product::class, ['status' => $updatedStatus]);
});

it('admin can update product seller', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $oldSeller = User::factory()->create(['id' => 1]);
    $newSeller = User::factory()->create(['id' => 2]);

    $product = Product::factory()->create(['seller_id' => $oldSeller->id]);

    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');

    login($user);

    $response = $this->putJson(action([AdminProductController::class, 'update'], $product->uuid), [
        'seller_id' => $newSeller->uuid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Product::class, ['seller_id' => $newSeller->id]);
});

it('admin can delete product', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    User::factory()->count(10)->create();
    $product = Product::factory()->create();

    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson(action([AdminProductController::class, 'update'], $product->uuid));

    $response->assertOk();

    $this->assertDatabaseMissing(Product::class, ['id' => $product->id]);
});
