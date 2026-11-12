<?php

namespace App\Http\Requests\Cities;

use App\Models\City;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreCityRequest extends AbstractCityRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            City::PARAM_CODE => [
                'required',
                'string',
                'min:2',
                'max:4',
                Rule::unique('cities'),
            ],
            City::PARAM_TITLE => [
                'string',
                'min:2',
                'max:100',
                Rule::unique('cities'),
            ],
        ];
    }
}
