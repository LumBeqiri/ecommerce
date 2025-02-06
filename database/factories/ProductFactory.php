<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'ulid' => Str::ulid(),
            'product_name' => $this->faker->name,
            'vendor_id' => Vendor::all()->random()->id,
            'status' => $this->faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
            'publish_status' => $this->faker->randomElement([Product::PUBLISHED, Product::DRAFT]),
            'discountable' => $this->faker->boolean,
            'origin_country_id' => Country::all()->random()->id,
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
                'status' => Product::AVAILABLE_PRODUCT,
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
                'status' => Product::UNAVAILABLE_PRODUCT,
            ];
        });
    }
}
