<?php

namespace App\Http\Requests\SourceProductTypes;

use App\Models\SourceProductType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreSourceProductTypeRequest',
    properties: [
        new OA\Property(property: 'code', type: 'string', example: 'toys'),
        new OA\Property(property: 'name', type: 'string', example: 'Игрушки'),
    ],
    type: 'object'
)]
class StoreSourceProductTypeRequest extends AbstractSourceProductTypeRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            SourceProductType::PARAM_CODE => [
                'required',
                'string',
                'min:2',
                'max:50',
                Rule::unique(SourceProductType::TABLE_NAME),
            ],
            SourceProductType::PARAM_NAME => [
                'required',
                'string',
                'min:2',
                'max:50',
                Rule::unique(SourceProductType::TABLE_NAME),
            ],
        ];
    }
}
