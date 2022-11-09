<?php

namespace Database\Seeders;

use App\Models\Media;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Database\Factories\MediaFactory;
use App\Models\{Cart, CartItem, User,Category, Discount, Product,Order, Variant};
use Database\Factories\DiscountFactory;

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
        Cart::truncate();
        CartItem::truncate();
        Product::truncate();
        Variant::truncate();
        Discount::truncate();
        // Media::truncate();
     
        DB::table('attribute_variant')->truncate();
        DB::table('category_product')->truncate();
        DB::table('order_product')->truncate();
        

        // in order to not send emails to fake accounts when seeding the db, we call flushEventListenres(); method
        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Discount::flushEventListeners();
        Cart::flushEventListeners();
        CartItem::flushEventListeners();

        //seed currencies table from an sql file
        $path = public_path('sql/currencySeeder.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $usersQuantity = 50;
        $categoriesQuantity = 30;
        $productsQuantity = 50;
        $variantsQuantity = 30;
        $ordersQuantity = 50;
        
        User::factory()->create([
            'name' => 'Lum Beqiri',
            'email' => 'lum@gmail.com',
            'password' => bcrypt('123123123')
        ]);

        User::factory($usersQuantity)->create();
        Discount::factory(5)->create();
        Category::factory($categoriesQuantity)->create();
    
        Product::factory($productsQuantity)->create()->each(
            function($product){
                $categories =  Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product->categories()->attach($categories);
            }
        );

        Variant::factory($variantsQuantity)->create();
        
        // Media::factory($imagesQuantity)->create();

        Order::factory($ordersQuantity)->create()->each(
            function($order){
                $products = Product::all()->random(mt_rand(1, 5))->pluck('id');
                $order->products()->attach($products);
            }
        );
        
        Cart::factory(10)->create();
        CartItem::factory(20)->create();
    }
}
