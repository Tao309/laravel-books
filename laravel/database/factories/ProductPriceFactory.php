<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductPrice>
 */
class ProductPriceFactory extends Factory
{
    public function definition(): array
    {
        return [
            ProductPrice::PARAM_PRODUCT_ID => Product::factory(),
            ProductPrice::PARAM_USER_ID => User::factory(),
            ProductPrice::PARAM_PRICE => fake()->numberBetween(100, 100000),
            ProductPrice::PARAM_DATE => fake()->dateTimeBetween('-1 year'),
        ];
    }
}
