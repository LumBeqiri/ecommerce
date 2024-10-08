<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Category::class;

    public function definition()
    {
        return [
            'ulid' => Str::ulid(),
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph(1),
            'slug' => $this->faker->unique()->word,
        ];
    }
}
