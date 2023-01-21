<?php

use App\Models\User;
use App\Models\Media;
use App\Models\Region;
use App\Models\Country;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Attribute;
use App\Models\TaxProvider;
use Database\Seeders\CurrencySeeder;
use App\Http\Controllers\Seller\SellerVariantAttributeController;

beforeEach(function(){
    Notification::fake();
    Bus::fake();
    $this->seed(CurrencySeeder::class);
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    User::factory()->create();
});

it('can add an attribute to a variant ', function(){

    $product = Product::factory()->available()->create();
    $attribute1 = Attribute::factory()->for($product)->create();
    $attribute2 = Attribute::factory()->for($product)->create();
    $variant = Variant::factory()->for($product)->create();
    $user = User::factory()->create();
 
    login($user);
  
    $response = $this->postJson(action([SellerVariantAttributeController::class, 'store'],[$variant->uuid]),
        [
            'product_attributes' => [$attribute1->uuid, $attribute2->uuid]
        ]
    );

    $response->assertStatus(200);

    $this->assertDatabaseHas('attribute_variant', [
        'variant_id' => $variant->id,
        'attribute_id' => $attribute1->id,
    ]);

    $this->assertDatabaseHas('attribute_variant', [
        'variant_id' => $variant->id,
        'attribute_id' => $attribute2->id,
    ]);
});


it('can not have duplicate attribute type in a variant ', function(){

    $product = Product::factory()->available()->create();
    $attribute1 = Attribute::factory()->for($product)->create(['attribute_type' => 'color', 'attribute_value' => 'blue']);
    $attribute2 = Attribute::factory()->for($product)->create(['attribute_type' => 'color', 'attribute_value' => 'red']);
    $variant = Variant::factory()->for($product)->create();
    $user = User::factory()->create();
 
    login($user);
  
    $response = $this->postJson(action([SellerVariantAttributeController::class, 'store'],[$variant->uuid]),
        [
            'product_attributes' => [$attribute1->uuid, $attribute2->uuid]
        ]
    );

    $response->assertStatus(422);

    $this->assertDatabaseMissing('attribute_variant', [
        'variant_id' => $variant->id,
        'attribute_id' => $attribute1->id,
    ]);

    $this->assertDatabaseMissing('attribute_variant', [
        'variant_id' => $variant->id,
        'attribute_id' => $attribute2->id,
    ]);
});


it('can remove attribute type from variant ', function(){

    $product = Product::factory()->available()->create();
    $attribute = Attribute::factory()->for($product)->create(['attribute_type' => 'color', 'attribute_value' => 'blue']);
    $variant = Variant::factory()->for($product)->create();
    $user = User::factory()->create();
 
    login($user);
  
    $response = $this->deleteJson(action([SellerVariantAttributeController::class, 'destroy'],[$variant->uuid,$attribute->uuid]));

    $response->assertStatus(200);

    $this->assertDatabaseMissing('attribute_variant', [
        'variant_id' => $variant->id,
        'attribute_id' => $attribute->id,
    ]);

});