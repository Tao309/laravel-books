<?php

namespace App\Http\Requests\Books;

use App\Http\Requests\RequestInterface;
use App\Models\Book;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends AbstractBookRequest
{
    public function rules(): array
    {
        $id = $this->route('book');

        return [
            Book::PARAM_TITLE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':255',
                Rule::unique(Book::TABLE_NAME)->ignore($id),
            ],
            Book::PARAM_ORIGINAL_TITLE => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':255',
                Rule::unique(Book::TABLE_NAME)->ignore($id),
            ],
            Book::PARAM_ISBN => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':10',
                RequestInterface::RULE_MAX . ':30',
                Rule::unique(Book::TABLE_NAME)->ignore($id),
            ],
            ...$this->getCommonRules(),
        ];
    }
}
