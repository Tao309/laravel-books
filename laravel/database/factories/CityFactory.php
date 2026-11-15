<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    public function definition(): array
    {
        $cityTitle = fake()->city();

        return [
            City::PARAM_TITLE => $cityTitle,
            City::PARAM_CODE => mb_strtoupper(mb_substr($cityTitle, 0, 3)),
        ];
    }

    public function krasnodar(): static
    {
        return $this->state([
            City::PARAM_ID => 1,
            City::PARAM_TITLE => 'Краснодар',
            City::PARAM_CODE => 'КРР',
        ]);
    }

    public function moscow(): static
    {
        return $this->state([
            City::PARAM_ID => 2,
            City::PARAM_TITLE => 'Москва',
            City::PARAM_CODE => 'МОВ',
        ]);
    }

    public function spb(): static
    {
        return $this->state([
            City::PARAM_ID => 3,
            City::PARAM_TITLE => 'Санкт-Петербург',
            City::PARAM_CODE => 'СПТ',
        ]);
    }
}
