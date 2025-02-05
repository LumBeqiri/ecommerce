<?php

use App\Http\Controllers\User\UserVariantController;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
use App\Models\Staff;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
use App\Models\Vendor;
use App\values\Roles;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->seed(CurrencySeeder::class);
    $this->seed(RoleAndPermissionSeeder::class);
    Notification::fake();
    Bus::fake();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
});

test('staff can create a variant', function () {

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create();
    Staff::factory()->create(['user_id' => $user->id, 'vendor_id' => $vendor->id]);
    $user->assignRole(Roles::STAFF);
    $user->givePermissionTo('update-products');
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variantData = [
        'variant_name' => 'Test Product',
        'manage_inventory' => true,
        'product_id' => $product->ulid,
        'product_description' => 'Test Product Description',
        'origin_country_id' => Country::first()->id,
        'stock' => 10,
        'sku' => '123456',
        'status' => Product::AVAILABLE_PRODUCT,
        'publish_status' => Product::PUBLISHED,
        'categories' => [Category::factory()->create()->ulid],
    ];

    login($user);

    $response = $this->postJson(action([UserVariantController::class, 'store']), $variantData);

    $response->assertOk();

    $this->assertDatabaseHas('variants', ['product_id' => $product->id]);

});

test('vendor can create a variant', function () {

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::VENDOR);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $productData = [
        'variant_name' => 'Test Product',
        'manage_inventory' => true,
        'product_id' => $product->ulid,
        'product_description' => 'Test Product Description',
        'origin_country_id' => Country::first()->id,
        'stock' => 10,
        'sku' => '123456',
        'status' => Product::AVAILABLE_PRODUCT,
        'publish_status' => Product::PUBLISHED,
        'categories' => [Category::factory()->create()->ulid],
    ];

    login($user);

    $response = $this->postJson(action([UserVariantController::class, 'store']), $productData);

    $response->assertOk();

    $this->assertDatabaseHas('variants', ['product_id' => $product->id]);

});

test('vendor can view its own variant', function () {

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::VENDOR);

    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $otherVendor = Vendor::factory()->create();
    $otherProduct = Product::factory()->create(['vendor_id' => $otherVendor->id]);
    $otherVariant = Variant::factory()->create(['product_id' => $otherProduct->id]);

    login($user);

    $response = $this->getJson(action([UserVariantController::class, 'show'], $variant->ulid));

    $response->assertOk();

    $response->assertJsonFragment([
        'id' => $variant->ulid,
    ]);
    $response->assertJsonMissing([
        'id' => $otherVariant->ulid,
    ]);

});

test('staff can view its own variant', function () {

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create();
    Staff::factory()->create(['user_id' => $user->id, 'vendor_id' => $vendor->id]);
    $user->assignRole(Roles::STAFF);

    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $otherVendor = Vendor::factory()->create();
    $otherProduct = Product::factory()->create(['vendor_id' => $otherVendor->id]);
    $otherVariant = Variant::factory()->create(['product_id' => $otherProduct->id]);

    login($user);

    $response = $this->getJson(action([UserVariantController::class, 'show'], $variant->ulid));

    $response->assertOk();

    $response->assertJsonFragment([
        'id' => $variant->ulid,
    ]);
    $response->assertJsonMissing([
        'id' => $otherVariant->ulid,
    ]);

});

test('Variant can be updated by vendor', function () {

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::VENDOR);

    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);
    $newVariantData = Variant::factory()->make()->toArray();

    login($user);

    $response = $this->putJson(action([UserVariantController::class, 'update'], $variant->ulid), $newVariantData);

    $response->assertOk();

    $response->assertJsonFragment([
        'variant_name' => $newVariantData['variant_name'],
        'sku' => $newVariantData['sku'],
        'variant_short_description' => $newVariantData['variant_short_description'],
    ]);
});

test('Variant can be deleted by vendor', function () {

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::VENDOR);

    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    login($user);

    $response = $this->deleteJson(action([UserVariantController::class, 'destroy'], $variant->ulid));

    $response->assertOk();

    $this->assertSoftDeleted('variants', ['id' => $variant->id]);

});
