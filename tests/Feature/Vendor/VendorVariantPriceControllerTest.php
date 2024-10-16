<?php

use App\Http\Controllers\Vendor\VendorVariantPriceController;
use App\Models\Country;
use App\Models\Currency;
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

test('vendor can create variant pricing', function () {
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    Country::factory()->create();
    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $user->assignRole('vendor');
    $user->givePermissionTo('update-products');

    login($user);

    $price = 120;
    $max_quantity = 5;
    $min_quantity = 2;

    $currency = Currency::where('code', 'EUR')->first();
    $response = $this->postJson(action([VendorVariantPriceController::class, 'store'], $variant->ulid), [
        'region_id' => $region->ulid,
        'price' => $price,
        'currency_id' => $currency->id,
        'min_quantity' => $min_quantity,
        'max_quantity' => $max_quantity,
    ]);
    $response->assertOk();

    $this->assertDatabaseHas(VariantPrice::class, ['variant_id' => $variant->id, 'region_id' => $region->id]);
});

test('vendor can update variant pricing', function () {
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    $region2 = Region::factory()->create();
    Country::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);
    $variantPrice = VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    $user->assignRole('vendor');
    login($user);

    $price = 120;
    $max_quantity = 5;
    $min_quantity = 2;

    $response = $this->putJson(
        action([VendorVariantPriceController::class, 'update'], ['variant' => $variant->ulid, 'variantPrice' => $variantPrice->ulid]),
        [
            'region_id' => $region2->ulid,
            'price' => $price,
            'min_quantity' => $min_quantity,
            'max_quantity' => $max_quantity,
        ]
    );

    $response->assertOk();

    $this->assertDatabaseHas(VariantPrice::class, ['variant_id' => $variant->id, 'region_id' => $region2->id]);
});

test('vendor can not update variant pricing of another vendor', function () {
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    $region2 = Region::factory()->create();
    Country::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);
    $variantPrice = VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    $user2 = User::factory()->create();

    $user2->assignRole('vendor');
    login($user2);

    $price = 120;
    $max_quantity = 5;
    $min_quantity = 2;

    $response = $this->putJson(
        action([VendorVariantPriceController::class, 'update'], ['variant' => $variant->ulid, 'variantPrice' => $variantPrice->ulid]),
        [
            'region_id' => $region2->ulid,
            'price' => $price,
            'min_quantity' => $min_quantity,
            'max_quantity' => $max_quantity,
        ]
    );

    $response->assertForbidden();

    $this->assertDatabaseHas(VariantPrice::class, ['variant_id' => $variant->id, 'region_id' => $region->id]);
});

test('vendor can delete variant pricing', function () {
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    Country::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);
    $variantPrice = VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    $user->assignRole('vendor');
    login($user);

    $response = $this->deleteJson(
        action([VendorVariantPriceController::class, 'destroy'], ['variant' => $variant->ulid, 'variantPrice' => $variantPrice->ulid])
    );

    $response->assertOk();

    $this->assertSoftDeleted(VariantPrice::class, ['variant_id' => $variant->id, 'region_id' => $region->id]);
});

test('vendor can not delete variant pricing of another vendor', function () {
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    Country::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);
    $variantPrice = VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    $user2 = User::factory()->create();
    $vendor2 = Vendor::factory()->create(['user_id' => $user2->id]);

    $user2->assignRole('vendor');
    login($user2);

    $response = $this->deleteJson(
        action([VendorVariantPriceController::class, 'destroy'], ['variant' => $variant->ulid, 'variantPrice' => $variantPrice->ulid])
    );

    $response->assertForbidden();

    $this->assertDatabaseHas(VariantPrice::class, ['variant_id' => $variant->id, 'region_id' => $region->id]);
});
