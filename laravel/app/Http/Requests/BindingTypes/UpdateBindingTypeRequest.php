<?php

namespace App\Http\Requests\BindingTypes;

use App\Http\Requests\RequestInterface;
use App\Models\BindingType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateBindingTypeRequest extends AbstractBindingTypeRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('binding_type');

        return [
            BindingType::PARAM_LABEL => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':5',
                RequestInterface::RULE_MAX . ':50',
                Rule::unique(BindingType::TABLE_NAME)->ignore($id),
            ],
        ];
    }
}
