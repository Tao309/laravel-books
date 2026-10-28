<?php

namespace App\Http\Requests\Cities;

use App\Models\City;
use Illuminate\Contracts\Validation\ValidationRule;
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
class StoreCityRequest extends AbstractCityRequest
{
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
}
