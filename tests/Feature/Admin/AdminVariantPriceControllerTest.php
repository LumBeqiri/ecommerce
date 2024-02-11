<?php

use App\Models\User;
use App\Models\Region;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Product;
use App\Models\Variant;
use App\Models\TaxProvider;
use App\Models\VariantPrice;
use Illuminate\Support\Facades\Bus;
use Database\Seeders\CurrencySeeder;
use Illuminate\Support\Facades\Notification;
use Database\Seeders\RoleAndPermissionSeeder;
use App\Http\Controllers\Admin\Product\AdminVariantController;
use App\Http\Controllers\Admin\Product\AdminVariantPriceController;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
});

it('admin can update variant pricing', function () {
    TaxProvider::factory()->create();
    $region = Region::factory()->create();
    $region2 = Region::factory()->create();
    Country::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);
    $variantPrice = VariantPrice::factory()->create(['variant_id' => $variant->id, 'region_id' => $region->id]);

    $user->assignRole('admin');
    login($user);

    $price = 120;
    $max_quantity = 5;
    $min_quantity = 2;

    $response = $this->putJson(
        action([AdminVariantPriceController::class, 'update'], ['variant' => $variant->uuid, 'variantPrice' => $variantPrice->uuid]),
        [
            'region_id' => $region2->uuid,
            'price' => $price,
            'min_quantity' => $min_quantity,
            'max_quantity' => $max_quantity,
        ]
    );

    $response->assertOk();

    $this->assertDatabaseHas(VariantPrice::class, ['variant_id' => $variant->id, 'region_id' => $region2->id]);
});
