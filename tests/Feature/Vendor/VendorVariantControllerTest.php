<?php

use App\Models\User;
use App\Models\Region;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Product;
use App\Models\Variant;
use App\Models\TaxProvider;
use Illuminate\Support\Facades\Bus;
use Database\Seeders\CurrencySeeder;
use Illuminate\Support\Facades\Notification;
use Database\Seeders\RoleAndPermissionSeeder;
use App\Http\Controllers\Vendor\VendorVariantController;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
});

it('vendor can update variant name', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user->assignRole('vendor');
    $updatedName = 'new name';
    login($user);

    $response = $this->putJson(action([VendorVariantController::class, 'update'], $variant->uuid), [
        'variant_name' => $updatedName,
        'product_id' => $product->uuid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['variant_name' => $updatedName]);
});

it('vendor can update variant sku', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user->assignRole('vendor');
    $updated = 'new sku';
    login($user);

    $response = $this->putJson(action([VendorVariantController::class, 'update'], $variant->uuid), [
        'sku' => $updated,
        'product_id' => $product->uuid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['sku' => $updated]);
});

it('vendor can update variant short description', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user->assignRole('vendor');
    $updated = 'new sku';
    login($user);

    $response = $this->putJson(action([VendorVariantController::class, 'update'], $variant->uuid), [
        'variant_short_description' => $updated,
        'product_id' => $product->uuid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['variant_short_description' => $updated]);
});

it('vendor can update variant long description', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user->assignRole('vendor');
    $updated = 'new description';
    login($user);

    $response = $this->putJson(action([VendorVariantController::class, 'update'], $variant->uuid), [
        'variant_long_description' => $updated,
        'product_id' => $product->uuid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['variant_long_description' => $updated]);
});



it('vendor can update variant stock', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id, 'stock' => 5]);

    $user->assignRole('vendor');
    $updated = 23;
    login($user);

    $response = $this->putJson(action([VendorVariantController::class, 'update'], $variant->uuid), [
        'stock' => $updated,
        'product_id' => $product->uuid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['stock' => $updated]);
});

it('vendor can not update variant with negative stock value', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id, 'stock' => 5]);

    $user->assignRole('vendor');
    $updated = -23;
    login($user);

    $response = $this->putJson(action([VendorVariantController::class, 'update'], $variant->uuid), [
        'stock' => $updated,
    ]);

    $response->assertStatus(422);
});

it('vendor can delete variant', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id, 'stock' => 5]);

    $user->assignRole('vendor');

    login($user);

    $response = $this->deleteJson(action([VendorVariantController::class, 'destroy'], $variant->uuid));

    $response->assertOk();

    $this->assertSoftDeleted(Variant::class, ['id' => $variant->id]);
});
