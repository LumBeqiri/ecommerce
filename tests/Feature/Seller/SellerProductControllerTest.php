<?php

use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use function Pest\Faker\faker;
use App\Services\CurrencySeederService;
use Database\Factories\SellerFactory;
use Illuminate\Http\UploadedFile;

it('can upload a product for sale ', function(){
    Storage::fake();
    CurrencySeederService::create();
    Category::factory()->count(2)->create();
    $user = User::factory()->create();

    login($user);

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->postJson(route('seller.create.product', [$user->uuid]),
        [
            'name' => 'water-bottle',
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
    $this->assertDatabaseHas(Product::class, ['name' => 'water-bottle']);

});


it('can update product name', function(){
    $old_name = 'old_name';
    $new_name = 'new_name';
    $seller = Seller::factory()
    ->has(Product::factory(['name' => $old_name])->count(1))
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

});



it('can update product description', function(){
    $old_description = 'old description';
    $new_description = 'new description';
    $seller = Seller::factory()
    ->has(Product::factory(['description' => $old_description])->count(1))
    ->create();

    $product = $seller->products()->first();
    
    login($seller);

 

    $response = $this->putJson(route('seller.update.product',['seller' => $seller->uuid,'product' => $product->uuid]),
        [
            'name' => $new_description
        ]
    );

    $response->assertStatus(200);
    
    expect($response->json())
        ->name->toBe($new_description);

});


