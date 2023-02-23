<?php

namespace Database\Factories;

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
            'variant_name' => $this->faker->name,
            'variant_short_description' => $this->faker->paragraph(1),
            'variant_long_description' => $this->faker->text,
            'stock' => $this->faker->randomNumber(1, 6),
            'manage_inventory' => $this->faker->boolean,
            'sku' => $this->faker->word(),
            'status' => $this->faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
            'publish_status' => $this->faker->randomElement([Product::PUBLISHED, Product::DRAFT]),
            'barcode' => $this->faker->randomNumber(1, 6),
            'ean' => $this->faker->randomNumber(1, 6),
            'upc' => $this->faker->randomNumber(1, 6),
            'allow_backorder' => $this->faker->boolean,
            'material' => $this->faker->word,
            'weight' => $this->faker->randomNumber(1, 6),
            'length' => $this->faker->randomNumber(1, 6),
            'height' => $this->faker->randomNumber(1, 6),
            'width' => $this->faker->randomNumber(1, 6),
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
                'status' => Product::AVAILABLE_PRODUCT,
            ];
        });
    }

    public function setUuid(string $uuid)
    {
        return $this->state(function (array $attributes) use ($uuid) {
            return [
                'uuid' => $uuid,
            ];
        });
    }
}
