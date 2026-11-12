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
use Illuminate\Validation\Rule;

class UpdateBookRequest extends AbstractBookRequest
{
    public function rules(): array
    {
        $id = $this->route('book');

        return [
            Book::PARAM_TITLE => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique(Book::TABLE_NAME)->ignore($id),
            ],
            Book::PARAM_ORIGINAL_TITLE => [
                'string',
                'min:2',
                'max:255',
                Rule::unique(Book::TABLE_NAME)->ignore($id),
            ],
            Book::PARAM_ISBN => [
                'string',
                'min:10',
                'max:30',
                Rule::unique(Book::TABLE_NAME)->ignore($id),
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
