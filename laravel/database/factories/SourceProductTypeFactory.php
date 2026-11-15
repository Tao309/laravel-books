<?php

namespace Database\Factories;

use App\Models\SourceProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SourceProductType>
 */
class SourceProductTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            SourceProductType::PARAM_CODE => fake('en_US')->word(),
            SourceProductType::PARAM_NAME => fake('ru_RU')->word(),
        ];
    }

    public function shoes(): static
    {
        return $this->state([
            SourceProductType::PARAM_CODE => 'shoes',
            SourceProductType::PARAM_NAME => 'Обувь',
        ]);
    }

    public function shirts(): static
    {
        return $this->state([
            SourceProductType::PARAM_CODE => 'shirts',
            SourceProductType::PARAM_NAME => 'Рубашки',
        ]);
    }

    public function beverages(): static
    {
        return $this->state([
            SourceProductType::PARAM_CODE => 'beverages',
            SourceProductType::PARAM_NAME => 'Напитки',
        ]);
    }

    public function medicines(): static
    {
        return $this->state([
            SourceProductType::PARAM_CODE => 'medicines',
            SourceProductType::PARAM_NAME => 'Лекарства',
        ]);
    }

    public function food(): static
    {
        return $this->state([
            SourceProductType::PARAM_CODE => 'food',
            SourceProductType::PARAM_NAME => 'Еда',
        ]);
    }
}
