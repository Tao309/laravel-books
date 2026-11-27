<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->company();

        return [
            Brand::PARAM_CODE => $title,
            Brand::PARAM_TITLE => $title,
        ];
    }
}
