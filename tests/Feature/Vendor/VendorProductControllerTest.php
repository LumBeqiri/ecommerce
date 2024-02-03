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
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorVariantController;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
});

it('vendor can update product name', function () {
    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $user->assignRole('vendor');
    $updatedName = 'new name';
    login($user);

    $response = $this->putJson(action([VendorProductController::class, 'update'], $product->uuid), [
        'product_name' => $updatedName,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Product::class, ['product_name' => $updatedName]);
});

it('vendor can not update product name of another vendor', function () {
    $oldName = 'old-name';
    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id,'product_name' => $oldName ]);

    $user2 = User::factory()->create();
    $vendor2 = Vendor::factory()->create(['user_id' => $user2->id]);

    $user2->assignRole('vendor');
    $updatedName = 'new name';
    login($user2);

    $response = $this->putJson(action([VendorProductController::class, 'update'], $product->uuid), [
        'product_name' => $updatedName,
    ]);

    $response->assertForbidden();

    $this->assertDatabaseHas(Product::class, ['product_name' => $oldName]);
});


it('vendor can update product status', function () {

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id, 'status'=> Product::AVAILABLE_PRODUCT]);

    $user->assignRole('vendor');
    $updatedValue = Product::UNAVAILABLE_PRODUCT;
    login($user);

    $response = $this->putJson(action([VendorProductController::class, 'update'], $product->uuid), [
        'status' => $updatedValue,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Product::class, ['status' => $updatedValue]);
});

it('vendor can update product', function () {


    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id, 'status'=> Product::AVAILABLE_PRODUCT]);

    $user->assignRole('vendor');
    $updatedValue = Product::UNAVAILABLE_PRODUCT;
    login($user);

    $response = $this->putJson(action([VendorProductController::class, 'update'], $product->uuid), [
        'status' => $updatedValue,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Product::class, ['status' => $updatedValue]);
});





it('vendor can delete product', function () {

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $user->assignRole('vendor');
    $user->hasPermissionTo('delete-products');

    login($user);

    $response = $this->deleteJson(action([VendorProductController::class, 'destroy'], $product->uuid));

    $response->assertOk();

    $this->assertSoftDeleted(Product::class, ['id' => $product->id]);
});


it('vendor can not delete product of another vendor', function () {


    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $user2 = User::factory()->create();
    $user2->assignRole('vendor');
    $user2->hasPermissionTo('delete-products');

    login($user2);

    $response = $this->deleteJson(action([VendorProductController::class, 'destroy'], $product->uuid));

    $response->assertForbidden();

});