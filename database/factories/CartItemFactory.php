<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Variant;
use App\Models\VariantPrice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $variant = Variant::all()->random();
        $variantPrice = VariantPrice::factory()->create();
        return [
            'ulid' => Str::ulid(),
            'cart_id' => Cart::all()->random()->id,
            'variant_id' => $variant->id,
            'variant_price_id' => $variantPrice->id,
            'price' => $variantPrice->price,
            'quantity' => $this->faker->numberBetween(3, 5),
        ];
    }
}
