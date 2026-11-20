<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shop>
 */
class ShopFactory extends Factory
{
    public function definition(): array
    {
        return [
            Shop::PARAM_TYPE => fake()->domainWord(),
            Shop::PARAM_DOMAIN => fake()->domainWord() . '.' . fake()->tld(),
        ];
    }

    public function ozon(): static
    {
        return $this->state([
            Shop::PARAM_ID => 1,
            Shop::PARAM_TYPE => 'ozon',
            Shop::PARAM_DOMAIN => 'ozon.ru',
        ]);
    }

    public function wb(): static
    {
        return $this->state([
            Shop::PARAM_ID => 2,
            Shop::PARAM_TYPE => 'wildberries',
            Shop::PARAM_DOMAIN => 'wildberries.ru',
        ]);
    }

    public function chitaiGorod(): static
    {
        return $this->state([
            Shop::PARAM_ID => 3,
            Shop::PARAM_TYPE => 'chitai-gorod',
            Shop::PARAM_DOMAIN => 'chitai-gorod.ru',
        ]);
    }
}
