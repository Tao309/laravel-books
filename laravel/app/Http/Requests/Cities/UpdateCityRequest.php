<?php

namespace App\Http\Requests\Cities;

use App\Models\City;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateCityRequest',
    properties: [
        new OA\Property(property: 'code', type: 'string', example: 'МОВ'),
        new OA\Property(property: 'title', type: 'string', example: 'Москва'),
    ],
    type: 'object'
)]
class UpdateCityRequest extends AbstractCityRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('city');

        return [
            City::CODE => [
                'required',
                'string',
                'min:2',
                'max:4',
                Rule::unique('cities')->ignore($id),
            ],
            City::TITLE => [
                'string',
                'min:2',
                'max:100',
                Rule::unique('cities')->ignore($id),
            ],
        ];
    }
}
