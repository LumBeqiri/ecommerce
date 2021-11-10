<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS =0');

        DB::table('order_product')->truncate();
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
            $product->orders()->attach($orders,
            [
                'quantity' =>$qty = $faker->randomDigit(),
                'total' => $qty * $product->price
            ]);
        }
    }
}
