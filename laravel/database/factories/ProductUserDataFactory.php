<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductUserData;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductUserData>
 */
class ProductUserDataFactory extends Factory
{
    public function definition(): array
    {
        $available = fake()->boolean(80);

        return [
            ProductUserData::PARAM_PRODUCT_ID => Product::factory(),
            ProductUserData::PARAM_USER_ID => User::factory(),
            ProductUserData::PARAM_AVAILABLE => $available,
            ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM => $available
                ? null
                : fake()->dateTimeBetween('-30 days'),
            ProductUserData::PARAM_AVAILABLE_DATE_FROM => $available
                ? fake()->dateTimeBetween('-30 days')
                : null,
            ProductUserData::PARAM_LISTEN_PRICE_VALUE => fake()->optional(0.7)->numberBetween(100, 100000),
            ProductUserData::PARAM_LISTEN_QTY_VALUE => fake()->optional(0.7)->numberBetween(1, 1000),
            ProductUserData::PARAM_RELEASE_DATE => fake()->optional()->dateTimeBetween('-1 year', '+1 year'),
            ProductUserData::PARAM_IS_ARCHIVE => fake()->boolean(10),
            ProductUserData::PARAM_COMMENT => fake()->optional(0.3)->sentence(),
        ];
    }
}
