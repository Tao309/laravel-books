<?php

namespace Database\Factories;

use App\Models\PublishingBrand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PublishingBrand>
 */
class PublishingBrandFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('ru_RU');

        $names = [
            fn () => $faker->randomElement([
                'Red',
                'Black',
                'White',
                'Dark',
                'Silver',
                'Golden',
            ]) . ' ' . $faker->randomElement([
                'Violet',
                'Phoenix',
                'World',
                'Books',
                'Star',
                'Moon',
            ]),

            fn () => $faker->randomElement([
                'Новая',
                'Тёмная',
                'Золотая',
                'Северная',
                'Тайная',
                'Вечная',
                'Последняя',
            ]) . ' ' . $faker->randomElement([
                'вселенная',
                'галактика',
                'легенда',
                'история',
                'реальность',
                'фантазия',
                'книга',
            ]),

            fn () => $faker->randomElement([
                'Мир',
                'Звёзды',
                'Хроники',
                'Легенды',
                'Тайны',
                'Грани',
                'Эпоха',
            ]) . ' ' . $faker->randomElement([
                'фэнтези',
                'фантастики',
                'романов',
                'приключений',
                'магии',
                'будущего',
            ]),

            fn () => $faker->randomElement([
                'Young Adult',
                'Nova Fiction',
                'Dark Romance',
                'Fantasy World',
                'Red Violet',
                'Silver Books',
            ]),
        ];

        return [
            PublishingBrand::PARAM_NAME => $faker->randomElement($names)(),
        ];
    }
}
