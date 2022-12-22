<?php

use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Seller\SellerVariantController;

it('can upload a product variant for sale ', function(){
    Storage::fake();
    User::factory()->count(10)->create();
    $product = Product::factory()->available()->create();
    $user = User::factory()->create();
 
    login($user);

    $file = UploadedFile::fake()->image('avatar.jpg');

    $variant = Variant::factory()->for($product)->create();

    $variant_data = $variant->toArray();

    unset($variant_data['id']);

    $variant_data['medias'] = [$file];
    //need to add sku manually
    $variant_data['sku'] = 'abc23';
    
    $response = $this->postJson(
            action([SellerVariantController::class, 'store'],
            $product->uuid),
            $variant_data
        );

    $response->assertStatus(200);

    $this->assertTrue(file_exists(public_path() . '/img/' . $file->hashName()));
    $this->assertDatabaseHas(Variant::class, ['variant_name' => $variant->variant_name]);

});


it('can update product variant name', function(){
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
    $old_data = 'old_name';
    $new_data = 'new_name';

    $seller = Seller::factory()->create();

    $product = Product::factory()->for($seller)->create();
    $variant = Variant::factory()->create(['short_description' => $old_data, 'product_id'=> $product->id]);
    
    login($seller);

    $response = $this->putJson(action([SellerVariantController::class, 'update'], $variant->uuid),[
        'short_description' => $new_data
    ]);

    $response->assertStatus(200);
    
    expect($response->json())
        ->short_description->toBe($new_data);

    $this->assertDatabaseHas(Variant::class, ['short_description' => $new_data]);

});


it('can update product variant long_description', function(){
    $old_data = 'old_name';
    $new_data = 'new_name';

    $seller = Seller::factory()->create();

    $product = Product::factory()->for($seller)->create();
    $variant = Variant::factory()->create(['long_description' => $old_data, 'product_id'=> $product->id]);
    
    login($seller);

    $response = $this->putJson(action([SellerVariantController::class, 'update'], $variant->uuid),[
        'long_description' => $new_data
    ]);

    $response->assertStatus(200);
    
    expect($response->json())
        ->long_description->toBe($new_data);

    $this->assertDatabaseHas(Variant::class, ['long_description' => $new_data]);

});

it('can update product variant stock', function(){
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

    $seller = Seller::factory()->create();

    Product::factory()->for($seller)->create();
    $variant = Variant::factory()->create();
    
    login($seller);

    $response = $this->deleteJson(action([SellerVariantController::class, 'destroy'], $variant->uuid));

    $response->assertStatus(200);
    
    $this->assertDatabaseMissing(Variant::class, ['id' => $variant->id]);

});

it('can not delete some elses product variant', function(){

    $seller = Seller::factory()->create();
    $rougeSeller = Seller::factory()->create();
    Product::factory()->for($seller)->create();
    $variant = Variant::factory()->create();
    
    login($rougeSeller);

    $response = $this->deleteJson(action([SellerVariantController::class, 'destroy'], $variant->uuid));

    $response->assertStatus(403);
    
    $this->assertDatabaseHas(Variant::class, ['id' => $variant->id]);

});