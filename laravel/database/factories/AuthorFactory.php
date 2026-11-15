<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    public function definition(): array
    {
        return [
            Author::PARAM_FIRST_NAME => fake()->firstName(),
            Author::PARAM_LAST_NAME => fake()->lastName(),
        ];
    }
}
