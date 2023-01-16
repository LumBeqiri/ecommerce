<?php

use App\Models\User;
use App\Models\Region;
use App\Models\Seller;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\TaxProvider;
use Illuminate\Http\UploadedFile;
use Database\Seeders\CurrencySeeder;
use App\Http\Controllers\Seller\SellerProductController;

beforeEach(function(){
    $this->seed(CurrencySeeder::class); 
    Notification::fake();
    Bus::fake();
});


it('can upload a product for sale ', function(){
    TaxProvider::factory()->create();
    $region1 = Region::factory()->create();
    $region2 = Region::factory()->create();
    Category::factory()->count(2)->create();
    $user = User::factory()->create();
    $productName = 'water-bottle';

    login($user);

    // $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->postJson(action([SellerProductController::class, 'store'],[$user->uuid]),
        [
            "name" => $productName,
            "variant_name" => "Example variant",
            "product_short_description" => "A short description of the product",
            "product_long_description" => "A longer description of the product with a maximum of 900 characters",
            "categories" => array("8213cb3b-a535-3606-b367-f7da47b2f231", "520742a6-cc3c-3ff2-a332-37ffb877e414", "1c21660d-abcf-3ab6-8418-0e67e56dd796"),
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

    // $this->assertTrue(file_exists(public_path() . '/img/' . $file->hashName()));
    $this->assertDatabaseHas(Product::class, ['name' => $productName]);

});


it('can update product name', function(){
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $old_name = 'old_name';
    $new_name = 'new_name';
    $seller = Seller::factory()
    ->has(Product::factory(['name' => $old_name])->available()->count(1))
    ->create();
    $product = $seller->products()->first();
    
    login($seller);

    $response = $this->putJson(action([SellerProductController::class, 'update'],['seller'=>$seller->uuid, 'product'=>$product->uuid]),
        [
            'name' => $new_name 
        ]
    );

    $response->assertStatus(200);

    
    expect($response->json())
        ->name->toBe($new_name);

    $this->assertDatabaseHas(Product::class, ['name' => $new_name]);

});



it('can update product short description', function(){
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $old_description = 'old description';
    $new_description = 'new description';
    $seller = Seller::factory()
        ->has(Product::factory(['product_short_description' => $old_description])->available()->count(1))
        ->create();

    $product = $seller->products()->first();
    
    login($seller);

    $response = $this->putJson(action([SellerProductController::class, 'update'],['seller' => $seller->uuid,'product' => $product->uuid]),
        [
            'product_short_description' => $new_description
        ]
    );

    $response->assertStatus(200);

    expect($response->json())
        ->product_short_description->toBe($new_description);

    $this->assertDatabaseHas(Product::class, ['product_short_description' => $new_description]);
    

});



it('can not update other users product', function(){
    TaxProvider::factory()->create();
    Region::factory()->create();
    Country::factory()->create();
    $old_description = 'old description';
    $new_description = 'new description';
    $seller = Seller::factory()
        ->has(Product::factory(['product_short_description' => $old_description])->available()->count(1))
        ->create();

    $product = $seller->products()->first();
    $unAuthorizedUser = Seller::factory()->create();
    login($unAuthorizedUser);

    $response = $this->putJson(action([SellerProductController::class, 'update'],['seller' => $seller->uuid,'product' => $product->uuid]),
        [
            'product_short_description' => $new_description
        ]
    );

    $response->assertStatus(403);
});
