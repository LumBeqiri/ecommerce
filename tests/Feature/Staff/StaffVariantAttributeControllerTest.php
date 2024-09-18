<?php

use App\Http\Controllers\Staff\StaffVariantAttributeController;
use App\Models\Attribute;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
});

it('staff can add variant attributes', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $vendorUser = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $staffUser = User::factory()->create();
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor->id]);

    $attribute1 = Attribute::factory()->create();
    $attribute2 = Attribute::factory()->create();

    $staffUser->assignRole('manager');
    $staffUser->givePermissionTo('update-products');
    $attributeulids = [$attribute1->ulid, $attribute2->ulid];

    login($staffUser);

    $response = $this->putJson(action([StaffVariantAttributeController::class, 'update'], $variant->ulid), [
        'attributes' => $attributeulids,
    ]);

    $response->assertOk();

    foreach ($attributeulids as $attributeulid) {
        $attributeId = Attribute::where('ulid', $attributeulid)->value('id');
        $this->assertDatabaseHas('attribute_variant', [
            'variant_id' => $variant->id,
            'attribute_id' => $attributeId,
        ]);
    }

});

it('staff can remove variant attributes', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $vendorUser = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);

    $staffUser = User::factory()->create();
    Staff::factory()->create(['user_id' => $staffUser->id, 'vendor_id' => $vendor->id]);

    $staffUser->assignRole('manager');
    $staffUser->givePermissionTo('update-products');

    $attribute1 = Attribute::factory()->create();
    $attribute2 = Attribute::factory()->create();

    $variant->attributes()->sync([$attribute1->id, $attribute2->id]);
    $attributeulids = [];

    login($staffUser);

    $response = $this->putJson(action([StaffVariantAttributeController::class, 'update'], $variant->ulid), [
        'attributes' => $attributeulids,
    ]);

    $response->assertOk();

    expect(DB::table('attribute_variant')->count())->toBe(0);

});
