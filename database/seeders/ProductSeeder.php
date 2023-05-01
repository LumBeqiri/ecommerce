<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productsQuantity = 50;
        Product::factory()->create(['uuid' => '35e11a0b-49ef-3bf3-a2ac-59b36423b6d1']);
        Product::factory()->create(['uuid' => '1fd3c554-2be9-3cfd-b52f-ca5d8244773a']);
        Product::factory()->create(['uuid' => '273bb17e-6b76-38ba-8262-1911b5eb6066']);
        Product::factory($productsQuantity)->create()->each(
            function ($product) {
                $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product->categories()->attach($categories);
            }
        );
    }
}
