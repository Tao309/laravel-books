<?php

namespace App\Http\Requests\BindingTypes;

use App\Models\BindingType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateBindingTypeRequest',
    properties: [
        new OA\Property(property: 'label', type: 'string', example: 'Мягкий переплёт'),
    ],
    type: 'object'
)]
class UpdateBindingTypeRequest extends FormRequest
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
        $id = $this->route('binding_type');

        return [
            BindingType::LABEL => [
                'required',
                'string',
                'min:5',
                'max:50',
                Rule::unique('binding_types')->ignore($id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            BindingType::LABEL.'.unique' => 'An Binding Type with this label already exists',
        ];
    }
}
