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
        Variant::factory(['status' => Product::AVAILABLE_PRODUCT, 'publish_status' => Product::PUBLISHED])->setUuid('a92f7c0d-5781-4367-b817-5cd9f9064184')->create();
        Variant::factory(['status' => Product::AVAILABLE_PRODUCT, 'publish_status' => Product::PUBLISHED])->setUuid('f6e89183-6b9d-4c17-9719-fbb4c02d5614')->create();
        Variant::factory(['status' => Product::AVAILABLE_PRODUCT, 'publish_status' => Product::PUBLISHED])->setUuid('8fe19731-ba71-44c4-a0af-ac1183cb6c26')->create();

        $variantsQuantity = 30;
        Variant::factory($variantsQuantity)->count($variantsQuantity)->create()->each(
            function ($variant) {
                $attributes = Attribute::all()->random()->pluck('id');
                $variant->attributes()->attach($attributes);
            }
        );
    }
}
