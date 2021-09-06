<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $country = $this->faker->unique()->randomElement(['India', 'Usa', 'Pakistan', 'Japan', 'Turkey', 'Egypt']);
        return [
            'country' => $country,
        ];
    }
}
