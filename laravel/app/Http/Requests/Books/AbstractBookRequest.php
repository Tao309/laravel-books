<?php

namespace App\Http\Requests\Books;

use App\DTOs\BookDTO;
use App\Http\Requests\RequestInterface;
use App\Models\Author;
use App\Models\BindingType;
use App\Models\Book;
use App\Models\BookSeries;
use App\Models\BookUserData;
use App\Models\PaperType;
use App\Models\PublishingBrand;
use App\Models\PublishingHouse;
use App\Rules\BookRatingRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class AbstractBookRequest extends FormRequest implements RequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            Book::PARAM_TITLE.'.unique' => 'A Book with this title already exists',
            Book::PARAM_ORIGINAL_TITLE.'.unique' => 'A Book with this original title already exists',
            Book::PARAM_ISBN.'.unique' => 'A Book with this ISBN already exists',
            Book::PARAM_BINDING_TYPE.'.id.unique' => 'BindingType is required',
            Book::PARAM_BINDING_TYPE.'.id.exists' => 'BindingType is not found',
            Book::PARAM_PAPER_TYPE.'.id.exists' => 'PaperType is not found',
            Book::PARAM_AUTHORS.'.*.id' => 'Author {:attribute} is not found',
            Book::PARAM_PUBLISHING_HOUSE.'.id.exists' => 'PublishingHouse is not found',
            Book::PARAM_PUBLISHING_BRAND.'.id.exists' => 'PublishingBrand is not found',
            Book::PARAM_BOOK_SERIES.'.id.exists' => 'BookSeries is not found',
        ];
    }

    public function toDTO(): BookDTO
    {
        return BookDTO::fromArray($this->validated());
    }

    protected function getCommonRules(): array
    {
        return [
            Book::PARAM_PAGES => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_INTEGER.':strict',
                RequestInterface::RULE_MIN_DIGITS.':2',
                RequestInterface::RULE_MAX_DIGITS.':5',
            ],
            Book::PARAM_CIRCULATION => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_INTEGER.':strict',
                RequestInterface::RULE_MIN_DIGITS.':3',
                RequestInterface::RULE_MAX_DIGITS.':10',
            ],
            Book::PARAM_SIZE => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN.':5',
                RequestInterface::RULE_MAX.':30',
            ],
            Book::PARAM_PUBLISH_YEAR => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_INTEGER.':strict',
                RequestInterface::RULE_DIGITS.':4',
                RequestInterface::RULE_MIN.':1990',
            ],
            Book::PARAM_LIVELIB_ID => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN.':1',
                RequestInterface::RULE_MAX.':15',
            ],
            Book::PARAM_GOODREADS_ID => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN.':1',
                RequestInterface::RULE_MAX.':15',
            ],
            Book::PARAM_FANTLAB_ID => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN.':1',
                RequestInterface::RULE_MAX.':15',
            ],
            Book::PARAM_LIVELIB_RATING => [
                RequestInterface::RULE_NULLABLE,
                new BookRatingRule,
                RequestInterface::RULE_DECIMAL.':1,2',
            ],
            Book::PARAM_GOODREADS_RATING => [
                RequestInterface::RULE_NULLABLE,
                new BookRatingRule,
                RequestInterface::RULE_DECIMAL.':1,2',
            ],

            // > relations
            Book::PARAM_AUTHORS => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_ARRAY,
            ],
            Book::PARAM_AUTHORS.'.*.'.Author::PARAM_ID => [
                RequestInterface::RULE_REQUIRED,
                Rule::exists(Author::TABLE_NAME, Author::PARAM_ID),
            ],

            Book::PARAM_BINDING_TYPE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_ARRAY,
            ],
            Book::PARAM_BINDING_TYPE.'.'.BindingType::PARAM_ID => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_INTEGER,
                Rule::exists(BindingType::TABLE_NAME, BindingType::PARAM_ID),
            ],

            Book::PARAM_PAPER_TYPE => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_ARRAY,
            ],
            Book::PARAM_PAPER_TYPE.'.'.PaperType::PARAM_ID => [
                Rule::requiredIf(
                    fn () => is_array($this->input(Book::PARAM_PAPER_TYPE))
                ),
                RequestInterface::RULE_INTEGER,
                Rule::exists(PaperType::TABLE_NAME, PaperType::PARAM_ID),
            ],

            Book::PARAM_PUBLISHING_HOUSE => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_ARRAY,
            ],
            Book::PARAM_PUBLISHING_HOUSE.'.'.PublishingHouse::PARAM_ID => [
                Rule::requiredIf(
                    fn () => is_array($this->input(Book::PARAM_PUBLISHING_HOUSE))
                ),
                RequestInterface::RULE_INTEGER,
                Rule::exists(PublishingHouse::TABLE_NAME, PublishingHouse::PARAM_ID),
            ],

            Book::PARAM_PUBLISHING_BRAND => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_ARRAY,
            ],
            Book::PARAM_PUBLISHING_BRAND.'.'.PublishingBrand::PARAM_ID => [
                Rule::requiredIf(
                    fn () => is_array($this->input(Book::PARAM_PUBLISHING_BRAND))
                ),
                RequestInterface::RULE_INTEGER,
                Rule::exists(PublishingBrand::TABLE_NAME, PublishingBrand::PARAM_ID),
            ],

            Book::PARAM_BOOK_SERIES => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_ARRAY,
            ],
            Book::PARAM_BOOK_SERIES.'.'.BookSeries::PARAM_ID => [
                Rule::requiredIf(
                    fn () => is_array($this->input(Book::PARAM_BOOK_SERIES))
                ),
                RequestInterface::RULE_INTEGER,
                Rule::exists(BookSeries::TABLE_NAME, BookSeries::PARAM_ID),
            ],

            Book::PARAM_BOOK_USER_DATA => [
                RequestInterface::RULE_SOMETIMES,
                RequestInterface::RULE_ARRAY,
            ],
            Book::PARAM_BOOK_USER_DATA.'.'.BookUserData::PARAM_RELEASE_DATE => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_DATE_FORMAT.':Y.m.d,Y-m-d\TH:i:s.v\Z',
            ],
            Book::PARAM_BOOK_USER_DATA.'.'.BookUserData::PARAM_LISTEN_PRICE_VALUE => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_INTEGER,
            ],
            Book::PARAM_BOOK_USER_DATA.'.'.BookUserData::PARAM_COMMENT => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MAX.':300',
            ],
            // < relations
        ];
    }
}
