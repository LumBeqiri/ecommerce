<?php

namespace Database\Factories;

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
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph(1),
            'seller_id' => User::all()->random()->id,
            'currency_id' => 2,
            'discount_id' => $this->faker->numberBetween($min = 0, $max = 30),
            'status' => $this->faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
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
}
