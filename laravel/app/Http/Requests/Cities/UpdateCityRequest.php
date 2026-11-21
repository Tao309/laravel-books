<?php

namespace App\Http\Requests\Cities;

use App\Http\Requests\RequestInterface;
use App\Models\City;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateCityRequest extends AbstractCityRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('city');

        return [
            City::PARAM_CODE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':4',
                Rule::unique(City::TABLE_NAME)->ignore($id),
            ],
            City::PARAM_TITLE => [
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':2',
                RequestInterface::RULE_MAX . ':100',
                Rule::unique(City::TABLE_NAME)->ignore($id),
            ],
        ];
    }
}
