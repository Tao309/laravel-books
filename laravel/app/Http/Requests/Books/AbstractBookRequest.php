<?php

namespace App\Http\Requests\Books;

use App\DTOs\BookDTO;
use App\Http\Requests\RequestInterface;
use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;

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
            Book::PARAM_ISBN.'.unique' => 'A Book with this isbn already exists',
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
}
