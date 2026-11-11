<?php

namespace App\Http\Requests\Products;

use App\Models\Product;
use App\Models\ProductUserData;
use Illuminate\Contracts\Validation\ValidationRule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ArchiveProductRequest',
    properties: [
        new OA\Property(
            property: Product::PARAM_SHOP_PRODUCT_ID,
            type: 'string',
            example: '3315320967'
        ),
        new OA\Property(
            property: ProductUserData::PARAM_IS_ARCHIVE,
            type: 'boolean',
            example: true,
        ),
    ],
    type: 'object',
)]
class ArchiveProductRequest extends AbstractProductRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            Product::PARAM_SHOP_PRODUCT_ID => [
                'required',
                'string',
                'min:5',
                'max:30',
            ],
            ProductUserData::PARAM_IS_ARCHIVE => [
                'required',
                'boolean',
            ],
        ];
    }
}
