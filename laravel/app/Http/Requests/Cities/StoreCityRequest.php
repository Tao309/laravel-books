<?php

namespace App\Http\Requests\Cities;

use App\Models\City;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreCityRequest',
    properties: [
        new OA\Property(property: 'code', type: 'string', example: 'МОВ'),
        new OA\Property(property: 'title', type: 'string', example: 'Москва'),
    ],
    type: 'object'
)]
class StoreCityRequest extends FormRequest
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
            City::CODE => [
                'required',
                'string',
                'min:2',
                'max:4',
                Rule::unique('cities'),
            ],
            City::TITLE => [
                'string',
                'min:2',
                'max:100',
                Rule::unique('cities'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            City::CODE.'.unique' => 'A City with this code already exists',
            City::TITLE.'.unique' => 'A City with this title already exists',
        ];
    }
}
