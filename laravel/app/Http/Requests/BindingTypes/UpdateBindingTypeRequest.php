<?php

namespace App\Http\Requests\BindingTypes;

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
                'required',
                'string',
                'min:5',
                'max:50',
                Rule::unique('binding_types')->ignore($id),
            ],
        ];
    }
}
