<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Seeder;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['status' => Product::AVAILABLE_PRODUCT,
            'publish_status' => Product::PUBLISHED,
            'stock' => 2000,
        ];
        Variant::factory($data + ['product_id' => 1])->setulid('01J82QRY0DE1PD2W4FD3P2AF9J')->create();
        Variant::factory($data + ['product_id' => 2])->setulid('01J82QS36RGDSA0EWZFPP3BVAH')->create();
        Variant::factory($data + ['product_id' => 3])->setulid('01J82QS7B6DD61YTV24BX5EJFZ')->create();

        $variantsQuantity = 30;
        Variant::factory($variantsQuantity)->count($variantsQuantity)->create()->each(
            function ($variant) {
                $attributes = Attribute::all()->random()->pluck('id');
                $variant->attributes()->attach($attributes);
            }
        );
    }
}
