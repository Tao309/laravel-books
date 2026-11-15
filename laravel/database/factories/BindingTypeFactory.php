<?php

namespace Database\Factories;

use App\Models\BindingType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BindingType>
 */
class BindingTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            BindingType::PARAM_LABEL => fake()->word(),
        ];
    }

    public function hardcover(): static
    {
        return $this->state([
            BindingType::PARAM_ID => 1,
            BindingType::PARAM_LABEL => 'Твёрдый переплёт',
        ]);
    }

    public function softcover(): static
    {
        return $this->state([
            BindingType::PARAM_ID => 2,
            BindingType::PARAM_LABEL => 'Мягкий переплёт',
        ]);
    }
}
