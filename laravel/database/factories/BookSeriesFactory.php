<?php

namespace Database\Factories;

use App\Models\BookSeries;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookSeries>
 */
class BookSeriesFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('ru_RU');

        $name = $faker->randomElement([
            'Звезды',
            'Миры',
            'Хроники',
            'Библиотека',
            'Коллекция',
        ]).' '.$faker->randomElement([
            'фантастики',
            'фэнтези',
            'современной прозы',
            'приключений',
            'романов',
        ]);

        return [
            BookSeries::PARAM_NAME => $name,
        ];
    }
}
