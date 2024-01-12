<?php

use App\Models\User;
use App\Models\Region;
use App\Models\Vendor;
use App\Models\Country;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Attribute;
use App\Models\TaxProvider;
use Illuminate\Support\Facades\Bus;
use Database\Seeders\CurrencySeeder;
use Illuminate\Support\Facades\Notification;
use Database\Seeders\RoleAndPermissionSeeder;
use App\Http\Controllers\Vendor\VendorVariantController;
use App\Http\Controllers\Vendor\VendorVariantAttributeController;

    beforeEach(function () {
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(CurrencySeeder::class);
        Notification::fake();
        Bus::fake();
    });

    it('vendor can update variant name and add variant attributes', function () {
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
    

