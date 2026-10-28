<?php

namespace App\Http\Requests\BindingTypes;

use App\Models\BindingType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreBindingTypeRequest',
    properties: [
        new OA\Property(property: 'label', type: 'string', example: 'Мягкий переплёт'),
    ],
    type: 'object'
)]
class StoreBindingTypeRequest extends AbstractBindingTypeRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            BindingType::LABEL => [
                'required',
                'string',
                'min:5',
                'max:50',
                Rule::unique('binding_types'),
            ],
        ];
    }
}
