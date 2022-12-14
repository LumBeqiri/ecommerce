<?php

namespace Database\Factories;

use App\Models\Discount;
use App\Models\User;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

class VariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Variant::class;
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'product_id' => Product::all()->random()->id,
            'sku' => $this->faker->word,
            'variant_name' => $this->faker->word,
            'short_description' => $this->faker->paragraph(1),
            'long_description' => $this->faker->paragraph(1),
            'price' => $this->faker->numberBetween($min = 1, $max = 500),
            'stock' => $this->faker->numberBetween($min = 0, $max = 30),
            'status' => $this->faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
        ];
    }


    /**
     * Indicate that the variant is available
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
