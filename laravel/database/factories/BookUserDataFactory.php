<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookUserData;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookUserData>
 */
class BookUserDataFactory extends Factory
{
    public function definition(): array
    {
        return [
            BookUserData::PARAM_BOOK_ID => Book::factory(),
            BookUserData::PARAM_USER_ID => User::factory(),
            BookUserData::PARAM_RELEASE_DATE => fake()->date(),
            BookUserData::PARAM_LISTEN_PRICE_VALUE => fake()->numberBetween(450, 9000),
            BookUserData::PARAM_COMMENT => fake()->sentence(),
        ];
    }
}
