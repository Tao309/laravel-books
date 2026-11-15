<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\City;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SourceProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            Product::PARAM_SHOP_PRODUCT_ID => fake()->numerify('############'),
            Product::PARAM_SHOP_PRODUCT_CODE => fake()->optional()->numerify('############'),
            Product::PARAM_TITLE => fake()->randomElement([
                'Футболка зелёная',
                'Ботинки зимние',
                'Кофта на весну',
                'Куртка демисезонная',
                'Джинсы классические',
                'Кроссовки спортивные',
                'Рубашка хлопковая',
                'Свитер шерстяной',
                'Шапка зимняя',
                'Рюкзак городской',
            ]),

            Product::PARAM_SHOP_ID => Shop::factory(),
            Product::PARAM_CITY_ID => fake()->boolean(70)
                ? City::factory()
                : null,
            Product::PARAM_USER_CREATED_ID => User::factory(),

            ...$this->sourceAttributes(),
        ];
    }

    private function sourceAttributes(): array
    {
        $sourceType = fake()->randomElement([
            Book::class,
            SourceProduct::class,
            null,
        ]);

        if (! $sourceType) {
            return [];
        }

        return [
            Product::PARAM_SOURCE_TYPE => $sourceType,
            Product::PARAM_SOURCE_ID => $sourceType::factory(),
        ];
    }

    public function forBook(): static
    {
        return $this->state(fn () => [
            Product::PARAM_SOURCE_TYPE => Book::class,
            Product::PARAM_SOURCE_ID => Book::factory(),
        ]);
    }

    public function forSourceProduct(): static
    {
        return $this->state(fn () => [
            Product::PARAM_SOURCE_TYPE => SourceProduct::class,
            Product::PARAM_SOURCE_ID => SourceProduct::factory(),
        ]);
    }
}
