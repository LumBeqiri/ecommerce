<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
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
            'mediable_id' => function (array $attributes) {
                return $attributes['mediable_type']::factory();
            },
            'mediable_type' => $this->faker->randomElement([
                Variant::class,
                Product::class,
            ]),
            'name' => $this->faker->word,
            'file_name' => $this->faker->word.'.'.$this->faker->fileExtension,
            'mime_type' => $this->faker->mimeType,
            'path' => $this->faker->url,
            'disk' => 'local',
            'collection' => $this->faker->word,
            'size' => $this->faker->randomNumber(),
        ];
    }
}
