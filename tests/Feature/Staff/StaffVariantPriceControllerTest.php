<?php

use App\Http\Controllers\Staff\StaffVariantPriceController;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Region;
use App\Models\Staff;
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

it('staff can create variant pricing', function () {
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    Country::factory()->create();
    $currency = Currency::where('code', 'EUR')->first();
    $vendorUser = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $staffUser = User::factory()->create();
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor->id]);

    $staffUser->assignRole('manager');
    $staffUser->givePermissionTo('update-products');

    login($staffUser);

    $price = 120;
    $max_quantity = 5;
    $min_quantity = 2;

    $response = $this->postJson(action([StaffVariantPriceController::class, 'store'], $variant->ulid), [
        'region_id' => $region->ulid,
        'price' => $price,
        'currency_id' => $currency->id,
        'min_quantity' => $min_quantity,
        'max_quantity' => $max_quantity,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(VariantPrice::class, ['variant_id' => $variant->id, 'region_id' => $region->id]);
});

it('staff can update variant pricing', function () {
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    $region2 = Region::factory()->create();
    Country::factory()->create();

    $vendorUser = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);
    $variantPrice = VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    $staffUser = User::factory()->create();
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor->id]);

    $staffUser->assignRole('manager');
    $staffUser->givePermissionTo('update-products');

    login($staffUser);

    $price = 120;
    $max_quantity = 5;
    $min_quantity = 2;

    $response = $this->putJson(
        action([StaffVariantPriceController::class, 'update'], ['variant' => $variant->ulid, 'variantPrice' => $variantPrice->ulid]),
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

it('staff can not update variant pricing of another vendor', function () {
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    $region2 = Region::factory()->create();
    Country::factory()->create();

    $vendorUser = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);
    $variantPrice = VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    $vendorUser2 = User::factory()->create();
    $vendor2 = Vendor::factory()->create(['user_id' => $vendorUser2->id]);

    $staffUser = User::factory()->create();
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor2->id]);

    $staffUser->assignRole('manager');
    $staffUser->givePermissionTo('update-products');

    login($staffUser);

    $price = 120;
    $max_quantity = 5;
    $min_quantity = 2;

    $response = $this->putJson(
        action([StaffVariantPriceController::class, 'update'], ['variant' => $variant->ulid, 'variantPrice' => $variantPrice->ulid]),
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

it('staff can delete variant pricing', function () {
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    Country::factory()->create();

    $vendorUser = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);
    $variantPrice = VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    $staffUser = User::factory()->create();
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor->id]);

    $staffUser->assignRole('manager');
    $staffUser->givePermissionTo('delete-products');

    login($staffUser);

    $response = $this->deleteJson(
        action([StaffVariantPriceController::class, 'destroy'], ['variant' => $variant->ulid, 'variantPrice' => $variantPrice->ulid])
    );

    $response->assertOk();

    $this->assertSoftDeleted(VariantPrice::class, ['variant_id' => $variant->id, 'region_id' => $region->id]);
});

it('staff can not delete variant pricing of another vendor', function () {
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    Country::factory()->create();

    $vendorUser = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);
    $variantPrice = VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    $vendorUser2 = User::factory()->create();
    $vendor2 = Vendor::factory()->create(['user_id' => $vendorUser2->id]);

    $staffUser = User::factory()->create();
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor2->id]);

    $staffUser->assignRole('manager');
    $staffUser->givePermissionTo('update-products');

    login($staffUser);

    $response = $this->deleteJson(
        action([StaffVariantPriceController::class, 'destroy'], ['variant' => $variant->ulid, 'variantPrice' => $variantPrice->ulid])
    );

    $response->assertForbidden();

    $this->assertDatabaseHas(VariantPrice::class, ['variant_id' => $variant->id]);
});
