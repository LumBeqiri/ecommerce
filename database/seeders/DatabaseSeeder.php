<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{User,Category, Image, Product,Order, SubCategory, Variant};
use Database\Factories\ImageFactory;
use Faker\Factory as Faker;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Seed this file
     * There's no need to seed CategoryProduct or OrderSeeder
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS =0');

        User::truncate();
        Category::truncate();
        Product::truncate();
        Variant::truncate();
        Image::truncate();
     
        DB::table('attribute_variant')->truncate();
        DB::table('category_product')->truncate();
        DB::table('order_product')->truncate();
        

        // in order to not send emails to fake accounts when seeding the db, we call flushEventListenres(); method
        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();

        $usersQuantity = 100;
        $categoriesQuantity = 30;
        $productsQuantity = 50;
        $variantsQuantity = 30;
        $ordersQuantity = 50;
        $imagesQuantity = 50;
        

        User::factory($usersQuantity)->create();
        Category::factory($categoriesQuantity)->create();
    
        Product::factory($productsQuantity)->create()->each(
            function($product){
                $categories =  Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product->categories()->attach($categories);
            }
        );

        Variant::factory($variantsQuantity)->create();
        
        Image::factory($imagesQuantity)->create();

        $ordersQuantity = 100;
        Order::factory($ordersQuantity)->create();

        Order::factory($ordersQuantity)->create()->each(
            function($order){
                $products = Product::all()->random(mt_rand(1, 5))->pluck('id');
                $order->products()->attach($products);
            }
        );
        
        // foreach(Product::all() as $product){
        //     $faker = Faker::create();
        //     $qty = $faker->randomDigit();
        // }
     
        //seed currencies table from an sql file
        $path = public_path('sql/currencySeeder.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);

    }
}
