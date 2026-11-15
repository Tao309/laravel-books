<?php

namespace Database\Factories;

use App\Models\SourceProduct;
use App\Models\SourceProductUserData;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SourceProductUserData>
 */
class SourceProductUserDataFactory extends Factory
{
    public function definition(): array
    {
        return [
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => SourceProduct::factory(),
            SourceProductUserData::PARAM_USER_ID => User::factory(),
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => fake()->numberBetween(450, 9000),
            SourceProductUserData::PARAM_COMMENT => fake()->sentence(),
        ];
    }
}
