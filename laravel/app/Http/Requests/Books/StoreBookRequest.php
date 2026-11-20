<?php

namespace App\Http\Requests\Books;

use App\Http\Requests\RequestInterface;
use App\Models\Book;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreBookRequest extends AbstractBookRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            Book::PARAM_TITLE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':255',
                Rule::unique(Book::TABLE_NAME),
            ],
            Book::PARAM_ORIGINAL_TITLE => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':255',
                Rule::unique(Book::TABLE_NAME),
            ],
            Book::PARAM_ISBN => [
                RequestInterface::RULE_NULLABLE,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':10',
                RequestInterface::RULE_MAX . ':30',
                Rule::unique(Book::TABLE_NAME),
            ],
            ...$this->getCommonRules(),
        ];
    }
}
