<?php

use App\Http\Controllers\Admin\Product\AdminVariantController;
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
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
});

test('admin can update variant name', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    User::factory()->create();
    Vendor::factory()->create();
    $product = Product::factory()->create();
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updatedName = 'new name';
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->ulid), [
        'variant_name' => $updatedName,
        'product_id' => $product->ulid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['variant_name' => $updatedName]);
});

test('admin can update variant sku', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    Vendor::factory()->create();
    $product = Product::factory()->create();
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = 'new sku';
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->ulid), [
        'sku' => $updated,
        'product_id' => $product->ulid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['sku' => $updated]);
});

test('admin can update variant short description', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    Vendor::factory()->create();
    $product = Product::factory()->create();
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = 'new sku';
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->ulid), [
        'variant_short_description' => $updated,
        'product_id' => $product->ulid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['variant_short_description' => $updated]);
});

test('admin can update variant long description', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    Vendor::factory()->create();
    $product = Product::factory()->create();
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = 'new description';
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->ulid), [
        'variant_long_description' => $updated,
        'product_id' => $product->ulid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['variant_long_description' => $updated]);
});

test('admin can not update variant with negative price', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    Vendor::factory()->create();
    Product::factory()->create();
    $variant = Variant::factory()->create();

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = -230;
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->ulid), [
        'variant_price' => $updated,
    ]);

    $response->assertStatus(422);
})->skip();

test('admin can update variant stock', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    Vendor::factory()->create();
    $product = Product::factory()->create();
    $variant = Variant::factory()->create(['product_id' => $product->id, 'stock' => 5]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = 23;
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->ulid), [
        'stock' => $updated,
        'product_id' => $product->ulid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['stock' => $updated]);
});

test('admin can not update variant with negative stock value', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    Vendor::factory()->create();
    Product::factory()->create();
    $variant = Variant::factory()->create(['stock' => 5]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = -23;
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->ulid), [
        'stock' => $updated,
    ]);

    $this->assertEquals(422, $response->status());

});

test('admin can delete variant', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    User::factory()->create();
    Vendor::factory()->create();
    Product::factory()->create();
    $variant = Variant::factory()->create(['stock' => 5]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson(action([AdminVariantController::class, 'update'], $variant->ulid));

    $response->assertOk();

    $this->assertSoftDeleted(Variant::class, ['id' => $variant->id]);
});
