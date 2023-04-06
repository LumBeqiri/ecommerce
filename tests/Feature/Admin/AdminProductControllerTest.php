<?php

use App\Http\Controllers\Admin\Product\AdminProductController;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
use App\Models\TaxProvider;
use App\Models\User;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->seed(CurrencySeeder::class);
    $this->seed(RoleAndPermissionSeeder::class);

    Notification::fake();
    Bus::fake();
});

it('can upload a product for sale ', function () {
    TaxProvider::factory()->create();
    $region1 = Region::factory()->create();
    $region2 = Region::factory()->create();
    Category::factory()->count(2)->create();
    $user = User::factory()->create();
    $user->assignRole('admin');
    $productName = 'water-bottle';

    login($user);

    // $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->postJson(action([AdminProductController::class, 'store']),
        [
            'product_name' => $productName,
            'variant_name' => 'Example variant',
            'product_short_description' => 'A short description of the product',
            'product_long_description' => 'A longer description of the product with a maximum of 900 characters',
            'categories' => ['8213cb3b-a535-3606-b367-f7da47b2f231', '520742a6-cc3c-3ff2-a332-37ffb877e414', '1c21660d-abcf-3ab6-8418-0e67e56dd796'],
            'status' => 'available',
            'publish_status' => 'published',
            'sku' => 'ABdC1ff223',
            'barcode' => '1234a5f6f7f89',
            'ean' => '987f6d5s4s321f',
            'upc' => '111d2f223s33d',
            'stock' => 10,
            'variant_short_description' => 'A short description of the variant',
            'variant_long_description' => 'A longer description of the variant with a maximum of 255 characters',
            'manage_inventory' => true,
            'allow_backorder' => false,
            'material' => 'cloth',
            'weight' => 10,
            'length' => 20,
            'height' => 30,
            'width' => 40,
            'product_attributes' => [
                [
                    'attribute_type' => 'Color',
                    'attribute_value' => 'Blue',
                ],
            ],
            'variant_prices' => [
                [
                    'region_id' => $region1->uuid,
                    'price' => 100,
                    'max_quantity' => null,
                    'min_quantity' => null,
                ],
                [
                    'region_id' => $region2->uuid,
                    'price' => 120,
                    'max_quantity' => null,
                    'min_quantity' => null,
                ],
            ],
        ]
    );
    $response->assertStatus(200);

    // $this->assertTrue(file_exists(public_path() . '/img/' . $file->hashName()));
    $this->assertDatabaseHas(Product::class, ['product_name' => $productName]);
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

it('admin can delete product', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    User::factory()->count(10)->create();
    $product = Product::factory()->create();

    $user = User::factory()->create(['name' => 'Lum']);
    $user->assignRole('admin');

    login($user);

    $response = $this->deleteJson(action([AdminProductController::class, 'destroy'], $product->uuid));

    $response->assertOk();

    $this->assertDatabaseMissing(Product::class, ['id' => $product->id]);
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
