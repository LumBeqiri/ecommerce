<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
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
        return [
            'name' => $this->faker->word,
            'sku' => $this->faker->word,
            'price' => $this->faker->numberBetween($min = 1, $max = 200) ,
            'weight'=> $this->faker->numberBetween($min = 1, $max = 1000),
            'size' => 'regular',
            'short_desc' => $this->faker->paragraph(1),
            'long_desc' => $this->faker->paragraph(1),
            'image_1' => $this->faker->randomElement(['1.jpg','2.jpg','3.jpg']),
            'image_2' => $this->faker->randomElement(['1.jpg','2.jpg','3.jpg']),
            'seller_id' => User::all()->random()->id,
            'currency_id' => 2,
            'stock' => $this->faker->numberBetween(1,10),
            'status' => $this->faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
        ];
    }
}
