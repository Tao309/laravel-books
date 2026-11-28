<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->jobTitle();

        return [
            Role::PARAM_CODE => fake()->unique()->slug(),
            Role::PARAM_TITLE => $title,
            Role::PARAM_DATE_CREATED => now(),
        ];
    }
}
