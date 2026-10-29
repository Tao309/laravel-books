<?php

namespace App\Http\Requests\BookSeries;

use App\DTOs\BookSeriesDTO;
use App\Http\Requests\RequestInterface;
use App\Models\BookSeries;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractBookSeriesRequest extends FormRequest implements RequestInterface
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            BookSeries::NAME.'.unique' => 'A BookSeries with this name already exists',
        ];
    }

    public function toDTO(): BookSeriesDTO
    {
        return BookSeriesDTO::fromArray($this->validated());
    }
}
