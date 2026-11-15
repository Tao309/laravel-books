<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductStock>
 */
class ProductStockFactory extends Factory
{
    public function definition(): array
    {
        return [
            ProductStock::PARAM_PRODUCT_ID => Product::factory(),
            ProductStock::PARAM_USER_ID => User::factory(),
            ProductStock::PARAM_QTY => fake()->numberBetween(0, 1000),
            ProductStock::PARAM_DATE => fake()->dateTimeBetween('-1 year'),
            ProductStock::PARAM_LOG => [
                'date' => fake()->dateTimeBetween('-1 year')->format('Y-m-d\TH:i:s.v\Z'),
                'diffQty' => fake()->numberBetween(-100, 100),
                'diffHours' => fake()->numberBetween(1, 168),
                'newStatus' => fake()->numberBetween(0, 1),
                'oldStatus' => fake()->numberBetween(0, 1),
                'getCurrentQty' => fake()->numberBetween(0, 1000),
                'getLastStockQty' => fake()->numberBetween(0, 1000),
                'limitStockPercent' => fake()->numberBetween(10, 50),
                'stockChangePercent' => fake()->randomFloat(2, -100, 100),
                'isStockDaysLimitPassed' => fake()->boolean(),
                'isStockMoreThanLimitChanged' => fake()->boolean(),
                'isCurrentQtyMoreThanLastStock' => fake()->boolean(),
            ],
        ];
    }
}
