<?php

namespace Database\Seeders;

use App\Auth\CustomUserProvider;
use App\DTOs\BookDTO;
use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\BookUserData;
use App\Models\PaperType;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Repositories\BookRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class BookSeeder extends AbstractSeeder
{
    public function __construct(
        private readonly BookRepository $repo
    ) {}

    public function run(): void
    {
        $user = $this->getFirstUser();

        Auth::attempt([
            CustomUserProvider::TOKEN => $user->remember_token,
        ], true);

        $authors = $this->getAuthors();
        $houses = $this->getPublishingHouses();
        $brands = $this->getPublishingBrands();
        $series = $this->getBookSeries();
        $bindingTypes = $this->getBindingTypes();
        $paperTypes = $this->getPaperTypes();

        $this->repo->create(BookDTO::fromArray([
            Book::PARAM_TITLE => 'Ловец Чудес',
            Book::PARAM_ISBN => '978-5-00-214640-6',
            Book::PARAM_PAGES => 576,
            Book::PARAM_CIRCULATION => 7000,
            Book::PARAM_SIZE => '20x12.5x0,1',
            Book::PARAM_PUBLISH_YEAR => 2024,

            Book::PARAM_PUBLISHING_HOUSE => [
                PublishingHouse::PARAM_ID => $houses->filter(function (PublishingHouse $house) {
                    return $house->name === 'Манн, Иванов и Фербер';
                })->firstOrFail()->id,
            ],
            Book::PARAM_PUBLISHING_BRAND => [
                PublishingBrand::PARAM_ID => $brands->filter(function (PublishingBrand $brand) {
                    return $brand->name === 'Молодежная литература. Фантастика и фэнтези';
                })->firstOrFail()->id,
            ],
            Book::PARAM_BOOK_SERIES => [
                BookSeries::PARAM_ID => $series->filter(function (BookSeries $series) {
                    return $series->name === 'Red Violet. Темные миры';
                })->firstOrFail()->id,
            ],
            Book::PARAM_BINDING_TYPE => [
                BindingType::PARAM_ID => $bindingTypes->filter(function (BindingType $bindingType) {
                    return $bindingType->label === 'Твёрдый переплёт';
                })->firstOrFail()->id,
            ],
            Book::PARAM_PAPER_TYPE => [
                PaperType::PARAM_ID => $paperTypes->filter(function (PaperType $paperType) {
                    return $paperType->label === 'Офсет пухлый';
                })->firstOrFail()->id,
            ],
            Book::PARAM_AUTHORS => [
                [
                    Author::PARAM_ID => $authors->filter(function (Author $author) {
                        return $author->first_name === 'Рита' && $author->last_name === 'Хоффман';
                    })->firstOrFail()->id,
                ],
            ],

            Book::PARAM_BOOK_USER_DATA => [
                BookUserData::PARAM_COMMENT => 'Комментарий для Ловец Чудес',
            ],
        ]));

        $this->repo->create(BookDTO::fromArray([
            Book::PARAM_TITLE => 'Хтонь за околицей',
            Book::PARAM_ISBN => '978-5-00250-999-7',
            Book::PARAM_PAGES => 256,
            Book::PARAM_CIRCULATION => 7000,
            Book::PARAM_SIZE => '1.9x13.5x21',
            Book::PARAM_PUBLISH_YEAR => 2026,

            Book::PARAM_PUBLISHING_HOUSE => [
                PublishingHouse::PARAM_ID => $houses->filter(function (PublishingHouse $house) {
                    return $house->name === 'Манн, Иванов и Фербер';
                })->firstOrFail()->id,
            ],
            Book::PARAM_BOOK_SERIES => [
                BookSeries::PARAM_ID => $series->filter(function (BookSeries $series) {
                    return $series->name === 'МИФ x Шуфлядка Писателя';
                })->firstOrFail()->id,
            ],
            Book::PARAM_BINDING_TYPE => [
                BindingType::PARAM_ID => $bindingTypes->filter(function (BindingType $bindingType) {
                    return $bindingType->label === 'Твёрдый переплёт';
                })->firstOrFail()->id,
            ],
            Book::PARAM_PAPER_TYPE => [
                PaperType::PARAM_ID => $paperTypes->filter(function (PaperType $paperType) {
                    return $paperType->label === 'Типографская';
                })->firstOrFail()->id,
            ],
            Book::PARAM_AUTHORS => [
                [
                    Author::PARAM_ID => $authors->filter(function (Author $author) {
                        return $author->first_name === 'Катерина' && $author->last_name === 'Глинистая';
                    })->firstOrFail()->id,
                ],
                [
                    Author::PARAM_ID => $authors->filter(function (Author $author) {
                        return $author->first_name === 'Саша' && $author->last_name === 'Бакушкина';
                    })->firstOrFail()->id,
                ],
                [
                    Author::PARAM_ID => $authors->filter(function (Author $author) {
                        return $author->first_name === 'Аметисса' && $author->last_name === 'Грэсс';
                    })->firstOrFail()->id,
                ],
            ],

            Book::PARAM_BOOK_USER_DATA => [
                BookUserData::PARAM_COMMENT => 'Комментарий для Хтонь за околицей',
            ],
        ]));

        $this->repo->create(BookDTO::fromArray([
            Book::PARAM_TITLE => 'Сияние',
            Book::PARAM_ORIGINAL_TITLE => 'The Shining',
            Book::PARAM_ISBN => '978-5-17-080493-1',
            Book::PARAM_PAGES => 544,
            Book::PARAM_CIRCULATION => 21000,
            Book::PARAM_SIZE => '4x14x21',
            Book::PARAM_PUBLISH_YEAR => 2025,
            Book::PARAM_LIVELIB_ID => '1000965735',
            Book::PARAM_GOODREADS_ID => '11588',
            Book::PARAM_FANTLAB_ID => 'edition115331',
            Book::PARAM_LIVELIB_RATING => 4.4,
            Book::PARAM_GOODREADS_RATING => 4.28,

            Book::PARAM_PUBLISHING_HOUSE => [
                PublishingHouse::PARAM_ID => $houses->filter(function (PublishingHouse $house) {
                    return $house->name === 'АСТ';
                })->firstOrFail()->id,
            ],
            Book::PARAM_BOOK_SERIES => [
                BookSeries::PARAM_ID => $series->filter(function (BookSeries $series) {
                    return $series->name === 'Кинг: книжная полка';
                })->firstOrFail()->id,
            ],
            Book::PARAM_BINDING_TYPE => [
                BindingType::PARAM_ID => $bindingTypes->filter(function (BindingType $bindingType) {
                    return $bindingType->label === 'Твёрдый переплёт';
                })->firstOrFail()->id,
            ],
            Book::PARAM_PAPER_TYPE => [
                PaperType::PARAM_ID => $paperTypes->filter(function (PaperType $paperType) {
                    return $paperType->label === 'Офсет';
                })->firstOrFail()->id,
            ],
            Book::PARAM_AUTHORS => [
                [
                    Author::PARAM_ID => $authors->filter(function (Author $author) {
                        return $author->first_name === 'Стивен' && $author->last_name === 'Кинг';
                    })->firstOrFail()->id,
                ],
            ],

            Book::PARAM_BOOK_USER_DATA => [
                BookUserData::PARAM_COMMENT => 'Комментарий для Сияние',
            ],
        ]));
    }

    private function getAuthors(): Collection
    {
        return Author::all();
    }

    private function getPublishingHouses(): Collection
    {
        return PublishingHouse::all();
    }

    private function getPublishingBrands(): Collection
    {
        return PublishingBrand::all();
    }

    private function getBookSeries(): Collection
    {
        return BookSeries::all();
    }

    private function getBindingTypes(): Collection
    {
        return BindingType::all();
    }

    private function getPaperTypes(): Collection
    {
        return PaperType::all();
    }
}
