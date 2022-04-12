<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

     protected $model = Image::class;

    public function definition()
    {
        $imageable = $this->imageable();
        return [
            'image' => $this->faker->image(),
            'imageable_id' => $imageable::factory(),
            'imageable_type' => $imageable,
            'title' => $this->faker->word,
        ];
    }


    public function imageable()
    {
        return $this->faker->randomElement([
            Product::class,
            Variant::class,
        ]);
    }
}
