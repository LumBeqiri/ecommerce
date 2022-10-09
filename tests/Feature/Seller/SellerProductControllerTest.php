<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\CurrencyService;
use Database\Seeders\CurrencySeeder;

use function Pest\Faker\faker;


it('can upload a product for sale ', function(){
    CurrencyService::create();
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

});