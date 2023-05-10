<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Region;
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
        Currency::truncate();
        User::truncate();
        Category::truncate();
        Cart::truncate();
        CartItem::truncate();
        Product::truncate();
        Variant::truncate();
        Discount::truncate();

        DB::table('attribute_variant')->truncate();
        DB::table('category_product')->truncate();
        DB::table('order_variant')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // in order to not send emails to fake accounts when seeding the db, we call flushEventListenres() method
        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Discount::flushEventListeners();
        Cart::flushEventListeners();
        CartItem::flushEventListeners();
        Variant::flushEventListeners();
        Region::flushEventListeners();

        $this->call([RoleAndPermissionSeeder::class]);
        $this->call([CurrencySeeder::class]);
        $this->call([TaxProviderSeeder::class]);
        $this->call([RegionSeeder::class]);
        $this->call([CountrySeeder::class]);
        $this->call([UserSeeder::class]);
        $this->call([DiscountRuleSeeder::class]);
        $this->call([DiscountSeeder::class]);
        $this->call([CategorySeeder::class]);
        $this->call([ProductSeeder::class]);
        $this->call([DiscountConditionSeeder::class]);
        $this->call(AttributeSeeder::class);
        $this->call(VariantSeeder::class);
        // $this->call(CartSeeder::class);
        // $this->call(CartItemSeeder::class);
        $this->call(VariantPriceSeeder::class);
    }
}
