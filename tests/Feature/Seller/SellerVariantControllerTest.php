<?php

use App\Models\User;
use App\Models\Region;
use App\Models\Seller;
use App\Models\Country;
use App\Models\Product;
use App\Models\Variant;
use App\Models\TaxProvider;
use Database\Seeders\CurrencySeeder;
use App\Http\Controllers\Seller\SellerVariantController;

beforeEach(function(){
    $this->seed(CurrencySeeder::class);
    Notification::fake();
    Bus::fake();
});

it('can upload a product variant for sale ', function(){
    TaxProvider::factory()->create();
    $region1 = Region::factory()->create();
    $region2 = Region::factory()->create();
    Country::factory()->create();
    Storage::fake();
    User::factory()->count(10)->create();
    $product = Product::factory()->available()->create();
    $user = User::factory()->create();
 
    login($user);
  
    $response = $this->postJson(action([SellerVariantController::class, 'store'],[$product->uuid]),
    [
        "variant_name" => "Example variant",
        "status" => "available",
        "publish_status" => "published",
        "sku" => "ABdC1ff223",
        "barcode" => "1234a5f6f7f89",
        "ean" => "987f6d5s4s321f",
        "upc" => "111d2f223s33d",
        "stock" => 10,
        "variant_short_description" => "A short description of the variant",
        "variant_long_description" => "A longer description of the variant with a maximum of 255 characters",
        "manage_inventory" => true,
        "allow_backorder" => false,
        "material" => "cloth",
        "weight" => 10,
        "length" => 20,
        "height" => 30,
        "width" => 40,
        "variant_prices" => array(
            array(
                "region_id" => $region1->uuid,
                "price" => 100
            ),
            array(
                "region_id" => $region2->uuid,
                "price" => 120
            )
        )            
    ]
);

    $response->assertStatus(200);

    $this->assertDatabaseHas(Variant::class, ['uuid' => $response->json('id')]);

});


it('can update product variant name', function(){
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $old_name = 'old_name';
    $new_name = 'new_name';

    $seller = Seller::factory()->create();

    $product = Product::factory()->for($seller)->create();

    $variant = Variant::factory()->create(['variant_name' => $old_name, 'product_id'=> $product->id]);
    
    login($seller);

    $response = $this->putJson(action([SellerVariantController::class, 'update'], $variant->uuid),[
        'variant_name' => $new_name
    ]);

    $response->assertStatus(200);
    
    expect($response->json())
        ->variant_name->toBe($new_name);

    $this->assertDatabaseHas(Variant::class, ['variant_name' => $new_name]);

});

it('can update product variant short_description', function(){
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $old_data = 'old_name';
    $new_data = 'new_name';

    $seller = Seller::factory()->create();

    $product = Product::factory()->for($seller)->create();
    $variant = Variant::factory()->create(['variant_short_description' => $old_data, 'product_id'=> $product->id]);
    
    login($seller);

    $response = $this->putJson(action([SellerVariantController::class, 'update'], $variant->uuid),[
        'variant_short_description' => $new_data
    ]);

    $response->assertStatus(200);

    
    expect($response->json())
        ->variant_short_description->toBe($new_data);

    $this->assertDatabaseHas(Variant::class, ['variant_short_description' => $new_data]);

});


it('can update product variant long_description', function(){
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $old_data = 'old_name';
    $new_data = 'new_name';

    $seller = Seller::factory()->create();

    $product = Product::factory()->for($seller)->create();
    $variant = Variant::factory()->create(['variant_long_description' => $old_data, 'product_id'=> $product->id]);
    
    login($seller);

    $response = $this->putJson(action([SellerVariantController::class, 'update'], $variant->uuid),[
        'variant_long_description' => $new_data
    ]);

    $response->assertStatus(200);
    
    expect($response->json())
        ->variant_long_description->toBe($new_data);

    $this->assertDatabaseHas(Variant::class, ['variant_long_description' => $new_data]);

});

it('can update product variant stock', function(){
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $old_data = 43;
    $new_data = 120;

    $seller = Seller::factory()->create();

    $product = Product::factory()->for($seller)->create();
    $variant = Variant::factory()->create(['stock' => $old_data, 'product_id'=> $product->id]);
    
    login($seller);

    $response = $this->putJson(action([SellerVariantController::class, 'update'], $variant->uuid),[
        'stock' => $new_data
    ]);

    $response->assertStatus(200);
    
    expect($response->json())
        ->stock->toBe($new_data);

    $this->assertDatabaseHas(Variant::class, ['stock' => $new_data]);

});


it('can update product variant status', function(){
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $old_data = Product::UNAVAILABLE_PRODUCT;
    $new_data = Product::AVAILABLE_PRODUCT;

    $seller = Seller::factory()->create();

    $product = Product::factory()->for($seller)->create();
    $variant = Variant::factory()->create(['status' => $old_data, 'product_id'=> $product->id]);
    
    login($seller);

    $response = $this->putJson(action([SellerVariantController::class, 'update'], $variant->uuid),[
        'status' => $new_data
    ]);

    $response->assertStatus(200);
    
    expect($response->json())
        ->status->toBe($new_data);

    $this->assertDatabaseHas(Variant::class, ['status' => $new_data]);

});


it('can not update some elses product variant', function(){
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $old_data = Product::UNAVAILABLE_PRODUCT;
    $new_data = Product::AVAILABLE_PRODUCT;

    $rougeSeller = Seller::factory()->create();
    $seller = Seller::factory()->create();

    $product = Product::factory()->for($seller)->create();
    $variant = Variant::factory()->create(['status' => $old_data, 'product_id'=> $product->id]);
    
    login($rougeSeller);

    $response = $this->putJson(action([SellerVariantController::class, 'update'], $variant->uuid),[
        'status' => $new_data
    ]);

    $response->assertStatus(403);
    
    $this->assertDatabaseHas(Variant::class, ['status' => $old_data]);

});


it('can delete product variant', function(){
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $seller = Seller::factory()->create();

    Product::factory()->for($seller)->create();
    $variant = Variant::factory()->create();
    
    login($seller);

    $response = $this->deleteJson(action([SellerVariantController::class, 'destroy'], $variant->uuid));

    $response->assertStatus(200);
    
    $this->assertDatabaseMissing(Variant::class, ['id' => $variant->id]);

});

it('can not delete some elses product variant', function(){
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();

    $seller = Seller::factory()->create();
    $rougeSeller = Seller::factory()->create();
    Product::factory()->for($seller)->create();
    $variant = Variant::factory()->create();
    
    login($rougeSeller);

    $response = $this->deleteJson(action([SellerVariantController::class, 'destroy'], $variant->uuid));

    $response->assertStatus(403);
    
    $this->assertDatabaseHas(Variant::class, ['id' => $variant->id]);

});