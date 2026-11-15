<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\BookUserData;
use App\Models\PaperType;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            Book::PARAM_TITLE => fake()->sentence(3),
            Book::PARAM_ORIGINAL_TITLE => fake()->optional()->sentence(3),
            Book::PARAM_ISBN => fake()->isbn13(),
            Book::PARAM_PAGES => fake()->numberBetween(100, 1200),
            Book::PARAM_CIRCULATION => fake()->numberBetween(1000, 50000),
            Book::PARAM_SIZE => fake()->randomElement([
                '84x108/32',
                '70x100/16',
                '60x90/16',
                '84x108/16',
            ]),
            Book::PARAM_PUBLISH_YEAR => fake()->numberBetween(1990, 2026),
            Book::PARAM_LIVELIB_ID => fake()->optional()->numerify('########'),
            Book::PARAM_GOODREADS_ID => fake()->optional()->numerify('########'),
            Book::PARAM_FANTLAB_ID => fake()->optional()->numerify('########'),
            Book::PARAM_LIVELIB_RATING => fake()->optional()->randomFloat(2, 1, 5),
            Book::PARAM_GOODREADS_RATING => fake()->optional()->randomFloat(2, 1, 5),
            Book::PARAM_ON_SALE_COUNT => fake()->numberBetween(0, 10000),
            Book::PARAM_BINDING_TYPE_ID => BindingType::factory(),
            Book::PARAM_PAPER_TYPE_ID => PaperType::factory(),
            Book::PARAM_PUBLISHING_HOUSE_ID => PublishingHouse::factory(),
            Book::PARAM_PUBLISHING_BRAND_ID => PublishingBrand::factory(),
            Book::PARAM_BOOK_SERIES_ID => BookSeries::factory(),
            Book::PARAM_USER_CREATED_ID => User::factory(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Book $book) {
            $book->bookUserData()->create([
                BookUserData::PARAM_USER_ID => Auth::id() ?? $book->user_created_id,
            ]);
        });
    }

    public function withAuthors(int $count = 1): static
    {
        return $this->hasAttached(
            Author::factory()->count($count),
            'authors'
        );
    }
}
