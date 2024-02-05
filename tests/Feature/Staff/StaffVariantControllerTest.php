<?php

use App\Http\Controllers\Staff\StaffVariantController;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
use App\Models\Staff;
use App\Models\TaxProvider;
use App\Models\User;
use App\Models\Variant;
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
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
});

it('staff can update variant name', function () {
    $vendorUser = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $staffUser = User::factory()->create();
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor->id]);

    $staffUser->assignRole('manager');
    $staffUser->givePermissionTo('update-products');
    $updatedName = 'new name';
    login($staffUser);

    $response = $this->putJson(action([StaffVariantController::class, 'update'], $variant->uuid), [
        'variant_name' => $updatedName,
        'product_id' => $product->uuid,
    ]);

    $response->assertOk();

    $this->assertDatabaseHas(Variant::class, ['variant_name' => $updatedName]);
});

it('staff can delete variant', function () {

    $vendorUser = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $staffUser = User::factory()->create();
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor->id]);

    $staffUser->assignRole('manager');
    $staffUser->givePermissionTo('delete-products');

    login($staffUser);

    $response = $this->deleteJson(action([StaffVariantController::class, 'destroy'], $variant->uuid));
    $response->assertOk();

    $this->assertSoftDeleted(Variant::class, ['id' => $variant->id]);
});
