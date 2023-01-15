<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\User;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Product::class;
    

    public function definition()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS =0');
        return [
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->name,
            'product_long_description' => $this->faker->paragraph(1),
            'product_short_description' => $this->faker->paragraph(1),
            'seller_id' => User::all()->random()->id,
            'status' => $this->faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
            'publish_status' => $this->faker->randomElement([Product::PUBLISHED, Product::DRAFT]),
            'discountable' => $this->faker->boolean,
            'origin_country' => Country::all()->random()->id,
            'thumbnail' => $this->faker->imageUrl(),
        ];
    }

    /**
     * Indicate that the product is available
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function available()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Product::AVAILABLE_PRODUCT
            ];
        });
    }


    /**
     * Indicate that the product is unavailable
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unavailable()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Product::UNAVAILABLE_PRODUCT
            ];
        });
    }
}
