<?php

namespace App\Http\Requests\Authors;

use App\Models\Author;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreAuthorRequest',
    properties: [
        new OA\Property(property: 'first_name', type: 'string', example: 'Фёдор'),
        new OA\Property(property: 'last_name', type: 'string', example: 'Достоевский'),
    ],
    type: 'object'
)]
class StoreAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            Author::FIRST_NAME => ['required', 'string', 'min:2', 'max:100', Rule::unique('authors')
                ->where(function (Builder $query) {
                    return $query->where('first_name', $this->first_name)->where('last_name', $this->last_name);
                })],
            Author::LAST_NAME => ['string', 'min:2', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            Author::FIRST_NAME.'.unique' => 'An author with this name already exists',
        ];
    }
}
