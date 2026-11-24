<?php

namespace App\Http\Requests\BookSeries;

use App\Http\Requests\RequestInterface;
use App\Models\BookSeries;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateBookSeriesRequest extends AbstractBookSeriesRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('book_series');

        return [
            BookSeries::PARAM_NAME => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':255',
                Rule::unique(BookSeries::TABLE_NAME)->ignore($id),
            ],
        ];
    }
}
