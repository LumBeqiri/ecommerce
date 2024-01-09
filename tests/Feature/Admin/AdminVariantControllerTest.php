<?php

use App\Http\Controllers\Admin\Product\AdminVariantController;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantPrice;
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

it('admin can update variant price', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Vendor::factory()->create();
    Product::factory()->count(10)->create();

    TaxProvider::factory()->create();
    $region = Region::factory()->create(['id' => 12]);
    $variant = Variant::factory()->create(['id' => 3]);
    VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $price_to_update = 230;
    $updated_price = null;

    if ($region->currency->has_cents) {
        $updated_price = $price_to_update * 100;
    } else {
        $updated_price = $price_to_update;
    }
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->uuid), [
        'variant_price' => $price_to_update,
        'region' => $region->uuid,
    ]);


    $response->assertOk();

    $this->assertDatabaseHas(VariantPrice::class, ['price' => $updated_price]);
})->todo();

it('admin can update variant name', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    User::factory()->count(10)->create();
    Vendor::factory()->create();
    $product = Product::factory()->create();
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updatedName = 'new name';
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->uuid), [
        'variant_name' => $updatedName,
        'product_id' => $product->uuid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['variant_name' => $updatedName]);
});

it('admin can update variant sku', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Vendor::factory()->create();
    $product = Product::factory()->create();
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = 'new sku';
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->uuid), [
        'sku' => $updated,
        'product_id' => $product->uuid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['sku' => $updated]);
});

it('admin can update variant short description', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Vendor::factory()->create();
    $product = Product::factory()->create();
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = 'new sku';
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->uuid), [
        'variant_short_description' => $updated,
        'product_id' => $product->uuid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['variant_short_description' => $updated]);
});

it('admin can update variant long description', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Vendor::factory()->create();
    $product = Product::factory()->create();
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = 'new description';
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->uuid), [
        'variant_long_description' => $updated,
        'product_id' => $product->uuid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['variant_long_description' => $updated]);
});

it('admin can not update variant with negative price', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Vendor::factory()->create();
    Product::factory()->count(10)->create();
    $variant = Variant::factory()->create();

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = -230;
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->uuid), [
        'variant_price' => $updated,
    ]);

    $response->assertStatus(422);
})->skip();

it('admin can update variant stock', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Vendor::factory()->create();
    $product = Product::factory()->create();
    $variant = Variant::factory()->create(['product_id' => $product->id,'stock' => 5]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = 23;
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->uuid), [
        'stock' => $updated,
        'product_id' => $product->uuid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['stock' => $updated]);
});

it('admin can not update variant with negative stock value', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Vendor::factory()->create();
    Product::factory()->count(10)->create();
    $variant = Variant::factory()->create(['stock' => 5]);

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updated = -23;
    login($user);

    $response = $this->putJson(action([AdminVariantController::class, 'update'], $variant->uuid), [
        'stock' => $updated,
    ]);

    $response->assertStatus(422);
});

it('admin can delete variant', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    User::factory()->count(10)->create();
    Vendor::factory()->create();
    Product::factory()->count(10)->create();
    $variant = Variant::factory()->create(['stock' => 5]);

    $user = User::factory()->create();
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson(action([AdminVariantController::class, 'update'], $variant->uuid));

    $response->assertOk();

    $this->assertSoftDeleted(Variant::class, ['id' => $variant->id]);
});
