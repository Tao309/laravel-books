<?php

namespace App\Http\Requests\BookSeries;

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
        $id = $this->route('books_series');

        return [
            BookSeries::PARAM_NAME => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique(BookSeries::TABLE_NAME)->ignore($id),
            ],
        ];
    }
}
