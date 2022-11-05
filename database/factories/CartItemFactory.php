<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'cart_id' => Cart::factory(),
            'variant_id' => Variant::factory(),
            'count' => $this->faker->numberBetween(3,5),
            'total_item_price' => $this->faker->numberBetween(20,42),
        ];
    }
}
