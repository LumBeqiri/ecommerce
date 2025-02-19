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
        $productsQuantity = 20;
        Product::factory()->create(['ulid' => '01J82QPEESBETHRAGX6VPZ3X04']);
        Product::factory()->create(['ulid' => '01J82QPKZ74SCXJV3A9MP93VF4']);
        Product::factory()->create(['ulid' => '01J82QPRFASD7ADMKEE92JCCBT']);
        Product::factory($productsQuantity)->create()->each(
            function ($product) {
                $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product->categories()->attach($categories);
            }
        );
    }
}
