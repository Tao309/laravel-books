<?php

namespace App\Http\Requests\Products;

use App\Models\Product;
use App\Models\ProductUserData;
use Illuminate\Contracts\Validation\ValidationRule;

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
