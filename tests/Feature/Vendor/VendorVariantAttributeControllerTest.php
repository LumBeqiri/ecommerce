<?php

use App\Http\Controllers\Vendor\VendorVariantAttributeController;
use App\Models\Attribute;
use App\Models\Country;
use App\Models\Product;
use App\Models\Region;
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

it('vendor can add variant attributes', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);
    $attribute1 = Attribute::factory()->create();
    $attribute2 = Attribute::factory()->create();

    $user->assignRole('vendor');
    $attributeUuids = [$attribute1->uuid, $attribute2->uuid];

    login($user);

    $response = $this->putJson(action([VendorVariantAttributeController::class, 'update'], $variant->uuid), [
        'attributes' => $attributeUuids,
    ]);

    $response->assertOk();

    foreach ($attributeUuids as $attributeUuid) {
        $attributeId = Attribute::where('uuid', $attributeUuid)->value('id');
        $this->assertDatabaseHas('attribute_variant', [
            'variant_id' => $variant->id,
            'attribute_id' => $attributeId,
        ]);
    }

});

it('vendor can remove variant attributes', function () {
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $user = User::factory()->create();
    $vendor = Vendor::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['vendor_id' => $vendor->id]);
    $variant = Variant::factory()->create(['product_id' => $product->id]);
    $attribute1 = Attribute::factory()->create();
    $attribute2 = Attribute::factory()->create();

    $variant->attributes()->sync([$attribute1->id, $attribute2->id]);

    $user->assignRole('vendor');
    $attributeUuids = [];

    login($user);

    $response = $this->putJson(action([VendorVariantAttributeController::class, 'update'], $variant->uuid), [
        'attributes' => $attributeUuids,
    ]);

    $response->assertOk();

    expect(DB::table('attribute_variant')->count())->toBe(0);

});
