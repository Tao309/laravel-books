<?php

namespace App\Http\Requests\Books;

use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\BookUserData;
use App\Models\PaperType;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Rules\BookRatingRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreBookRequest',
    properties: [
        new OA\Property(property: Book::PARAM_TITLE, type: 'string', example: 'Сияние'),
        new OA\Property(property: Book::PARAM_ORIGINAL_TITLE, type: 'string', example: 'The Shining'),
        new OA\Property(property: Book::PARAM_ISBN, type: 'string', example: '978-5-17-080493-1'),
        new OA\Property(property: Book::PARAM_PAGES, type: 'integer', example: '544'),
        new OA\Property(property: Book::PARAM_CIRCULATION, type: 'integer', example: '21000'),
        new OA\Property(property: Book::PARAM_SIZE, type: 'integer', example: '4x14x21'),
        new OA\Property(property: Book::PARAM_PUBLISH_YEAR, type: 'integer', example: '2025'),
        new OA\Property(property: Book::PARAM_LIVELIB_ID, type: 'integer', example: '1000965735'),
        new OA\Property(property: Book::PARAM_GOODREADS_ID, type: 'integer', example: '11588'),
        new OA\Property(property: Book::PARAM_FANTLAB_ID, type: 'integer', example: 'edition115331'),
        new OA\Property(property: Book::PARAM_LIVELIB_RATING, type: 'float', example: '4.4'),
        new OA\Property(property: Book::PARAM_GOODREADS_RATING, type: 'float', example: '4.28'),
        new OA\Property(
            property: Book::PARAM_BINDING_TYPE,
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: '1'),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_PAPER_TYPE,
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: '1'),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_AUTHORS,
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: '1'),
                ],
                type: 'object'
            )
        ),
        new OA\Property(
            property: Book::PARAM_PUBLISHING_HOUSE,
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: '1'),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_PUBLISHING_BRAND,
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: '1'),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_BOOK_SERIES,
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: '1'),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: Book::PARAM_BOOK_USER_DATA,
            properties: [
                new OA\Property(property: BookUserData::PARAM_RELEASE_DATE, type: 'string', example: '2026-10-24'),
                new OA\Property(property: BookUserData::PARAM_LISTEN_PRICE_VALUE, type: 'integer', example: 500),
                new OA\Property(property: BookUserData::PARAM_COMMENT, type: 'string', example: 'Ждём пока отзывы'),
            ],
            type: 'object'
        ),
    ],
    type: 'object'
)]
class StoreBookRequest extends AbstractBookRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            Book::PARAM_TITLE => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique(Book::TABLE_NAME),
            ],
            Book::PARAM_ORIGINAL_TITLE => [
                'string',
                'min:2',
                'max:255',
                Rule::unique(Book::TABLE_NAME),
            ],
            Book::PARAM_ISBN => [
                'string',
                'min:10',
                'max:30',
                Rule::unique(Book::TABLE_NAME),
            ],
            Book::PARAM_PAGES => [
                'integer:strict',
                'min_digits:2',
                'max_digits:5',
            ],
            Book::PARAM_CIRCULATION => [
                'integer:strict',
                'min_digits:3',
                'max_digits:10',
            ],
            Book::PARAM_SIZE => [
                'string',
                'min:5',
                'max:30',
            ],
            Book::PARAM_PUBLISH_YEAR => [
                'required',
                'integer:strict',
                'min_digits:4',
                'max_digits:4',
            ],
            Book::PARAM_LIVELIB_ID => [
                'string',
                'min:1',
                'max:15',
            ],
            Book::PARAM_GOODREADS_ID => [
                'string',
                'min:1',
                'max:15',
            ],
            Book::PARAM_FANTLAB_ID => [
                'string',
                'min:1',
                'max:15',
            ],
            Book::PARAM_LIVELIB_RATING => [
                new BookRatingRule,
                'decimal:1,2',
            ],
            Book::PARAM_GOODREADS_RATING => [
                new BookRatingRule,
                'decimal:1,2',
            ],

            // > relations
            Book::PARAM_AUTHORS => [
                'required',
                'array',
            ],
            Book::PARAM_AUTHORS.'.*.id' => [
                'required',
                Rule::exists(Author::TABLE_NAME, 'id'),
            ],
            Book::PARAM_BINDING_TYPE => [
                'required',
                Rule::exists(BindingType::TABLE_NAME, 'id'),
            ],
            Book::PARAM_PAPER_TYPE => [
                Rule::exists(PaperType::TABLE_NAME, 'id'),
            ],
            Book::PARAM_PUBLISHING_HOUSE => [
                Rule::exists(PublishingHouse::TABLE_NAME, 'id'),
            ],
            Book::PARAM_PUBLISHING_BRAND => [
                Rule::exists(PublishingBrand::TABLE_NAME, 'id'),
            ],
            Book::PARAM_BOOK_SERIES => [
                Rule::exists(BookSeries::TABLE_NAME, 'id'),
            ],
            Book::PARAM_BOOK_USER_DATA => [
                'sometimes',
                'array',
            ],
            Book::PARAM_BOOK_USER_DATA.'.'.BookUserData::PARAM_RELEASE_DATE => [
                'sometimes',
                'date_format:Y.m.d',
            ],
            Book::PARAM_BOOK_USER_DATA.'.'.BookUserData::PARAM_LISTEN_PRICE_VALUE => [
                'sometimes',
                'integer',
            ],
            Book::PARAM_BOOK_USER_DATA.'.'.BookUserData::PARAM_COMMENT => [
                'sometimes',
                'string',
                'max:300',
            ],
            // < relations
        ];
    }
}
