<?php

namespace Database\Factories;

use App\Models\SourceProduct;
use App\Models\SourceProductType;
use App\Models\SourceProductUserData;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends Factory<SourceProduct>
 */
class SourceProductFactory extends Factory
{
    public function definition(): array
    {
        $faker = fake('ru_RU');

        $products = [
            'Футболка',
            'Кофта',
            'Толстовка',
            'Свитер',
            'Куртка',
            'Пальто',
            'Брюки',
            'Джинсы',
            'Шорты',
            'Платье',
            'Юбка',
            'Рубашка',
            'Ботинки',
            'Кроссовки',
            'Кеды',
            'Сапоги',
            'Туфли',
            'Кросс-боди',
            'Рюкзак',
            'Сумка',
        ];

        $colors = [
            'чёрная',
            'белая',
            'красная',
            'зелёная',
            'синяя',
            'серая',
            'бежевая',
            'коричневая',
            'бордовая',
            'голубая',
        ];

        $seasons = [
            'на весну',
            'на лето',
            'на осень',
            'на зиму',
            'демисезонная',
            'зимняя',
            'летняя',
        ];

        $formats = [
            fn () => $faker->randomElement($products) . ' ' .
                $faker->randomElement($colors),

            fn () => $faker->randomElement($products) . ' ' .
                $faker->randomElement($seasons),

            fn () => $faker->randomElement($products) . ' ' .
                $faker->randomElement($colors) . ' ' .
                $faker->randomElement($seasons),
        ];

        return [
            SourceProduct::PARAM_TITLE => $faker->randomElement($formats)(),
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE_ID => SourceProductType::factory(),
            SourceProduct::PARAM_USER_CREATED_ID => User::factory(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (SourceProduct $sourceProduct) {
            $sourceProduct->sourceProductUserData()->create([
                SourceProductUserData::PARAM_USER_ID => Auth::id() ?? $sourceProduct->user_created_id,
            ]);
        });
    }
}
