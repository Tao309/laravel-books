<?php

namespace App\Http\Requests\Cities;

use App\Models\City;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
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
class UpdateCityRequest extends FormRequest
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

    public function messages(): array
    {
        return [
            City::CODE.'.unique' => 'A City with this code already exists',
            City::TITLE.'.unique' => 'A City with this title already exists',
        ];
    }
}
