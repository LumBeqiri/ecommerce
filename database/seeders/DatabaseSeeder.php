<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{User,Category,Product,Order};
use Faker\Factory as Faker;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * First seed this file
     * Then seed OrderSeeder
     * There's no need to seed CategoryProduct
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS =0');

        User::truncate();
        Category::truncate();
        Product::truncate();
     
        DB::table('category_product')->truncate();
        DB::table('order_product')->truncate();
        

        // in order to not send emails to fake accounts when seeding the db, we call flushEventListenres(); method
        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();

        $usersQuantity = 1000;
        $categoriesQuantity = 30;
        $productsQuantity = 1000;
        $ordersQuantity = 100;
        

        User::factory($usersQuantity)->create();
        Category::factory($categoriesQuantity)->create();
        Product::factory($productsQuantity)->create()->each(
            function($product){
                $categories =  Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product->categories()->attach($categories);
            }
        );

        $ordersQuantity = 100;
        Order::factory($ordersQuantity)->create();
        
        foreach(Product::all() as $product){
            $faker = Faker::create();
            $qty = $faker->randomDigit();
           // echo($qty);
            $total = $qty * $product->price;
          //  echo($product->name . "-" . $product->price . " qty = " . $qty . " total = " . $total);
          //  echo("\n");
            $orders = Order::inRandomOrder()->take(rand(1,3))->pluck('id');
            // $product->orders()->attach($orders,
            // [
            //     'quantity' =>$qty = $faker->randomDigit(),
            //     'total' => $qty * $product->price
            // ]);
        }
     
                

    }
}
