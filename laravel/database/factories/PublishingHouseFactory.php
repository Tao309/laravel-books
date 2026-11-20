<?php

namespace Database\Factories;

use App\Models\PublishingHouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PublishingHouse>
 */
class PublishingHouseFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('ru_RU');

        $names = [
            fn () => $faker->randomElement([
                'Север',
                'Восток',
                'Орион',
                'Альфа',
                'Горизонт',
            ]) . ' ' . $faker->randomElement([
                'Пресс',
                'Книга',
                'Медиа',
            ]),

            fn () => $faker->randomElement([
                'Издательство',
                'Книжный дом',
                'Издательский дом',
            ]) . ' «' . $faker->randomElement([
                'Роман',
                'История',
                'Легенда',
                'Фантазия',
                'Вселенная',
                'Горизонт',
                'Созвездие',
                'Феникс',
                'Литература',
                'Мир',
                'Слово',
                'Классика',
            ]) . '»',

            fn () => $faker->randomElement([
                'Новая',
                'Большая',
                'Современная',
                'Русская',
            ]) . ' ' . $faker->randomElement([
                'книга',
                'библиотека',
                'литература',
                'коллекция',
            ]),
        ];

        return [
            PublishingHouse::PARAM_NAME => $faker->randomElement($names)(),
        ];
    }
}
