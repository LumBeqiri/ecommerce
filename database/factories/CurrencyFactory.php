<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $currencyNames = ['Leke', 'Dollars', 'Euro'];
        return [
            'name' => $currencyName = $this->faker->randomElement($currencyNames),
            'code' => match($currencyName){'Leke' => 'ALL', 'Dollars' => 'USD', 'Euro' => 'EUR'},
            'symbol' => match($currencyName){'Leke' => 'Lek', 'Dollars' => '$', 'Euro' => '€'},
        ];
    }
}
