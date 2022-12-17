<?php

use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use Database\Seeders\CurrencySeeder;
use Illuminate\Http\UploadedFile;

it('can upload a product for sale ', function(){
    Storage::fake();
    $this->seed(CurrencySeeder::class);
    Category::factory()->count(2)->create();
    $user = User::factory()->create();
    $productName = 'water-bottle';

    login($user);

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->postJson(route('seller.create.product', [$user->uuid]),
        [
            'name' => $productName,
            'sku' => 'llllusasew',
            'variant_name' => 'test',
            'short_description' => 'faker()->paragraph(1)',
            'long_description' => 'faker()->paragraph(2)',
            'currency_id' => 2,
            'stock' => 4,
            'status' => Product::AVAILABLE_PRODUCT,
            'categories' => [1,2],
            'price' => 200,
            'medias' => [$file]
        ]
    );

    $response->assertStatus(201);

    $this->assertTrue(file_exists(public_path() . '/img/' . $file->hashName()));
    $this->assertDatabaseHas(Product::class, ['name' => $productName]);

});


it('can update product name', function(){
    $old_name = 'old_name';
    $new_name = 'new_name';
    $seller = Seller::factory()
    ->has(Product::factory(['name' => $old_name])->available()->count(1))
    ->create();

    $product = $seller->products()->first();
    
    login($seller);

    $response = $this->putJson(route('seller.update.product',['seller' => $seller->uuid,'product' => $product->uuid]),
        [
            'name' => $new_name
        ]
    );

    $response->assertStatus(200);
    
    expect($response->json())
        ->name->toBe($new_name);

    $this->assertDatabaseHas(Product::class, ['name' => $new_name]);

});



it('can update product description', function(){
    $old_description = 'old description';
    $new_description = 'new description';
    $seller = Seller::factory()
        ->has(Product::factory(['description' => $old_description])->available()->count(1))
        ->create();

    $product = $seller->products()->first();
    
    login($seller);

    $response = $this->putJson(route('seller.update.product',['seller' => $seller->uuid,'product' => $product->uuid]),
        [
            'description' => $new_description
        ]
    );

    $response->assertStatus(200);

    expect($response->json())
        ->description->toBe($new_description);

    $this->assertDatabaseHas(Product::class, ['description' => $new_description]);
    

});


