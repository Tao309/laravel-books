<?php

namespace App\Http\Requests\SourceProducts;

use App\Models\SourceProduct;
use App\Models\SourceProductType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreSourceProductRequest',
    properties: [
        new OA\Property(property: 'title', type: 'string', example: 'Рубашка льняная с рукавами на лето'),

        new OA\Property(
            property: SourceProduct::PARAM_SOURCE_PRODUCT_TYPE,
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: '1'),
            ],
            type: 'object'
        ),
    ],
    type: 'object'
)]
class StoreSourceProductRequest extends AbstractSourceProductRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            SourceProduct::PARAM_TITLE => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique(SourceProduct::TABLE_NAME),
            ],
            // > relations
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => [
                'required',
                Rule::exists(SourceProductType::TABLE_NAME, SourceProductType::PARAM_ID),
            ],
            // < relations
        ];
    }
}
