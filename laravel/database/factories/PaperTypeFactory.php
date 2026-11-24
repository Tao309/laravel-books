<?php

namespace Database\Factories;

use App\Models\PaperType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaperType>
 */
class PaperTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            PaperType::PARAM_LABEL => fake()->unique()->words(2, true),
            PaperType::PARAM_DESCRIPTION => fake()->words(8, true),
        ];
    }

    public function typographic(): static
    {
        return $this->state([
            PaperType::PARAM_ID => 1,
            PaperType::PARAM_LABEL => 'Типографская',
            PaperType::PARAM_DESCRIPTION => 'Бумага средней плотности, светло-серая, слегка шероховатая',
        ]);
    }

    public function offset(): static
    {
        return $this->state([
            PaperType::PARAM_ID => 2,
            PaperType::PARAM_LABEL => 'Офсет',
            PaperType::PARAM_DESCRIPTION => 'Стандартная плотная бумага, белая и гладкая',
        ]);
    }

    public function newsprint(): static
    {
        return $this->state([
            PaperType::PARAM_ID => 3,
            PaperType::PARAM_LABEL => 'Газетная',
            PaperType::PARAM_DESCRIPTION => null,
        ]);
    }
}
