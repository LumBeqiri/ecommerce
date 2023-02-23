<?php

namespace Database\Seeders;

use App\Models\CartItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CartItem::factory(20)->create();
    }
}
