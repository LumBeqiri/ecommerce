<?php

use App\Http\Controllers\User\Products\UserProductController;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
use App\Models\Staff;
use App\Models\TaxProvider;
use App\Models\User;
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

test('Product can be created by vendor', function () {

    User::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::VENDOR);

    $productData = [
        'product_name' => 'Test Product',
        'product_description' => 'Test Product Description',
        'origin_country_id' => Country::first()->id,
        'status' => Product::AVAILABLE_PRODUCT,
        'publish_status' => Product::PUBLISHED,
        'categories' => [Category::factory()->create()->ulid],
    ];

    login($user);

    $response = $this->postJson(action([UserProductController::class, 'store']), $productData);

    $response->assertOk();

    $this->assertDatabaseHas('products', ['product_name' => $productData['product_name']]);

});

test('Product can be created by staff', function () {

    User::factory()->create();

    $user = User::factory()->create();
    Staff::factory()->create(['user_id' => $user->id, 'vendor_id' => Vendor::factory()->create()->id]);
    $user->assignRole(Roles::STAFF);
    $user->givePermissionTo('create-products');

    $productData = [
        'product_name' => 'Test Product',
        'product_description' => 'Test Product Description',
        'origin_country_id' => Country::first()->id,
        'status' => Product::AVAILABLE_PRODUCT,
        'publish_status' => Product::PUBLISHED,
        'categories' => [Category::factory()->create()->ulid],
    ];

    login($user);

    $response = $this->postJson(action([UserProductController::class, 'store']), $productData);

    $response->assertOk();

    $this->assertDatabaseHas('products', ['product_name' => $productData['product_name']]);

});

test('vendor can view its own products', function () {

    User::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::VENDOR);

    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $otherVendor = Vendor::factory()->create();
    $otherProduct = Product::factory()->create(['vendor_id' => $otherVendor->id]);

    login($user);

    $response = $this->getJson(action([UserProductController::class, 'index']));

    $response->assertOk();

    $response->assertJsonFragment([
        'id' => $product->ulid,
    ]);
    $response->assertJsonMissing([
        'id' => $otherProduct->ulid,
    ]);

});

test('vendor can show its own product', function () {

    User::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::VENDOR);

    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    login($user);

    $response = $this->getJson(action([UserProductController::class, 'show'], $product->ulid));

    $response->assertOk();

    $response->assertJsonFragment([
        'id' => $product->ulid,
    ]);
});

test('vendor cannot view another vendor\'s product', function () {

    User::factory()->create();

    $user = User::factory()->create();
    Vendor::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::VENDOR);

    $otherVendor = Vendor::factory()->create();
    $product = Product::factory()->create(['vendor_id' => $otherVendor->id]);

    login($user);

    $response = $this->getJson(action([UserProductController::class, 'show'], $product->ulid));

    $response->assertStatus(403);

    $response->assertJsonMissing([
        'id' => $product->ulid,
    ]);
});

test('staff can view its own products', function () {

    User::factory()->count(3)->create();

    $user = User::factory()->create();
    $staff = Staff::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::STAFF);

    $product = Product::factory()->create(['vendor_id' => $staff->vendor->id]);

    $otherVendor = Vendor::factory()->create();
    $otherProduct = Product::factory()->create(['vendor_id' => $otherVendor->id]);

    login($user);

    $response = $this->getJson(action([UserProductController::class, 'index']));

    $response->assertOk();

    $response->assertJsonFragment([
        'id' => $product->ulid,
    ]);
    $response->assertJsonMissing([
        'id' => $otherProduct->ulid,
    ]);

});

test('Product can be updated by vendor', function () {

    User::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::VENDOR);

    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    $newProductData = Product::factory()->make()->toArray();

    login($user);

    $response = $this->putJson(action([UserProductController::class, 'update'], $product->ulid), $newProductData);

    $response->assertOk();

    $response->assertJsonFragment([
        'product_name' => $newProductData['product_name'],
    ]);
});

test('Product can be updated by staff', function () {

    User::factory()->count(3)->create();

    $user = User::factory()->create();
    $staff = Staff::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::STAFF);
    $user->givePermissionTo('update-products');

    $product = Product::factory()->create(['vendor_id' => $staff->vendor->id]);

    $newProductData = Product::factory()->make()->toArray();

    login($user);

    $response = $this->putJson(action([UserProductController::class, 'update'], $product->ulid), $newProductData);

    $response->assertOk();

    $response->assertJsonFragment([
        'product_name' => $newProductData['product_name'],
    ]);
});

test('Product can be deleted by vendor', function () {

    User::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $user->assignRole(Roles::VENDOR);

    $product = Product::factory()->create(['vendor_id' => $vendor->id]);

    login($user);

    $response = $this->deleteJson(action([UserProductController::class, 'destroy'], $product->ulid));

    $response->assertOk();

    $this->assertSoftDeleted('products', ['id' => $product->id]);

});
