<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

     protected $model = Media::class;

    public function definition()
    {
        $mediable = $this->mediable();
        return [
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->word(),
            'file_name' => $this->faker->word(),
            'mime' => $this->faker->mimeType(),
            'path' => $this->faker->image(),
            'disk' => 'local',
            'hash' => $this->faker,
            'size' => $this->faker->filesize(),
            'mediable_id' => $mediable::factory(),
            'mediable_type' => $mediable,
        
        ];
    }


    public function mediable()
    {
        return $this->faker->randomElement([
            Product::class,
            Variant::class,
        ]);
    }
}
