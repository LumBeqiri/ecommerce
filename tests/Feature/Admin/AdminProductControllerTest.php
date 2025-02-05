<?php

use App\Http\Controllers\Admin\Product\AdminProductController;
use App\Http\Controllers\Product\ProductThumbnailController;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Vendor;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {

    $this->seed(CurrencySeeder::class);
    $this->seed(RoleAndPermissionSeeder::class);

    Notification::fake();
    Bus::fake();
});

test('admin can update product name', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Vendor::factory()->create();
    $product = Product::factory()->create();

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updatedName = 'new name';
    login($user);

    $response = $this->putJson(action([AdminProductController::class, 'update'], $product->ulid), [
        'product_name' => $updatedName,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Product::class, ['product_name' => $updatedName]);
});

test('admin can update product category', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Vendor::factory()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create();
    $product->categories()->attach($category);

    $newCategory = Category::factory()->create();

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    login($user);

    $response = $this->putJson(action([AdminProductController::class, 'update'], $product->ulid), [
        'categories' => [$category->ulid, $newCategory->ulid],
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('category_product', [
        'product_id' => $product->id,
        'category_id' => $newCategory->id,
    ]);
    $this->assertDatabaseHas('category_product', [
        'product_id' => $product->id,
        'category_id' => $category->id,
    ]);

});

test('admin can delete product', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Vendor::factory()->create();
    $product = Product::factory()->create();

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson(action([AdminProductController::class, 'destroy'], $product->ulid));

    $response->assertOk();

    $this->assertSoftDeleted(Product::class, ['id' => $product->id]);
});

test('admin can update product status', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->count(10)->create();
    Vendor::factory()->create();
    $product = Product::factory()->unavailable()->create();

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');
    $updatedStatus = Product::AVAILABLE_PRODUCT;
    login($user);

    $response = $this->putJson(action([AdminProductController::class, 'update'], $product->ulid), [
        'status' => $updatedStatus,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Product::class, ['status' => $updatedStatus]);
});

test('can upload product thumbnail', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
    Vendor::factory()->create();
    $product = Product::factory(['thumbnail' => ''])->create();

    $file = UploadedFile::fake()->image('avatar.jpg');

    $user = User::factory()->create(['email' => 'lum@test.com']);
    $user->assignRole('admin');

    login($user);

    $response = $this->postJson(action([ProductThumbnailController::class, 'store'], ['product' => $product->ulid]),
        [
            'thumbnail' => $file,
        ]
    );

    $response->assertStatus(200);

    expect($response->json())
        ->thumbnail->toBe($file->hashname());

    $this->assertTrue(file_exists(public_path().'/img/'.$file->hashName()));
    $this->assertDatabaseHas(Product::class, ['thumbnail' => $file->hashName()]);
})->todo();
