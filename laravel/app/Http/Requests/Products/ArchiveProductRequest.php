<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\RequestInterface;
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
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':5',
                RequestInterface::RULE_MAX . ':30',
            ],
            ProductUserData::PARAM_IS_ARCHIVE => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_BOOLEAN,
            ],
        ];
    }
}
