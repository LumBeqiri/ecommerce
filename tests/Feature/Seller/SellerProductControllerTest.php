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
    CurrencySeederService::create();
    Category::factory()->count(2)->create();
    $user = User::factory()->create();

    login($user);



    $response = $this->postJson(route('sell_product', [$user->uuid]),
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
            'price' => 200
        ]
    );

    $response->assertStatus(201);


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

    $response = $this->putJson(route('update_product',['seller' => $seller->uuid,'product' => $product->uuid]),
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

    $response = $this->putJson(route('update_product',['seller' => $seller->uuid,'product' => $product->uuid]),
        [
            'name' => $new_description
        ]
    );

    $response->assertStatus(200);
    
    expect($response->json())
        ->name->toBe($new_description);

});


// it('can update product image', function(){

//     Storage::fake('storage');

//     $file = UploadedFile::fake()->image('test.jpg');

//     $seller = Seller::factory()
//     ->has(Product::factory()->count(1))
//     ->create();

//     $product = $seller->products()->first();
    
//     login($seller);

//     $response = $this->putJson(route('update_product',['seller' => $seller->uuid,'product' => $product->uuid]),
//         [
//             'images' => [$file]
//         ]
//     );

//     $response->assertStatus(200);

    
//     Storage::disk('public')->assertExists('test.jpg');

// });

