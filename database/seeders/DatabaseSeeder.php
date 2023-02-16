<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Discount;
use App\Models\DiscountRule;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        DB::table('attribute_variant')->truncate();
        DB::table('category_product')->truncate();
        DB::table('order_product')->truncate();

        // in order to not send emails to fake accounts when seeding the db, we call flushEventListenres() method
        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Discount::flushEventListeners();
        Cart::flushEventListeners();
        CartItem::flushEventListeners();

        $this->call([CurrencySeeder::class]);
        $this->call([RoleAndPermissionSeeder::class]);
        $this->call([CountrySeeder::class]);
        $this->call([TaxProviderSeeder::class]);
        $this->call([RegionSeeder::class]);

        $usersQuantity = 50;
        $categoriesQuantity = 30;
        $productsQuantity = 50;
        $variantsQuantity = 30;
        $ordersQuantity = 50;

        $adminUser = User::factory()->create([
            'uuid' => 'f4e367e1-aefe-33de-8e38-5f8b2ef1bead',
            'name' => 'Lum Beqiri',
            'email' => 'lum@gmail.com',
            'password' => bcrypt('123123123'),
        ]);

        $adminUser->assignRole('admin');

        User::factory()->create([
            'uuid' => '0eaf6d30-9b51-11ed-a8fc-0242ac120002',
            'name' => 'Drin Beqiri',
            'email' => 'drin@gmail.com',
            'password' => bcrypt('123123123'),
        ]);
        DiscountRule::factory()->count(5)->create();

        User::factory($usersQuantity)->create();
        Discount::factory(5)->create();
        Category::factory($categoriesQuantity)->create();

        Product::factory($productsQuantity)->create()->each(
            function ($product) {
                $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product->categories()->attach($categories);
            }
        );

        $this->call(AttributeSeeder::class);

        Variant::factory($variantsQuantity)->count($variantsQuantity)->create()->each(
            function ($variant) {
                $attributes = Attribute::all()->random()->pluck('id');
                $variant->attributes()->attach($attributes);
            }
        );

        // Media::factory($imagesQuantity)->create();

        // Order::factory($ordersQuantity)->create()->each(
        //     function($order){
        //         $products = Product::all()->random(mt_rand(1, 5))->pluck('id');
        //         $order->products()->attach($products);
        //     }
        // );

        Cart::factory(10)->create();
        CartItem::factory(20)->create();
    }
}
