<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\RequestInterface;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreProductRequest extends AbstractProductRequest
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
            Product::PARAM_SHOP => [
                RequestInterface::RULE_REQUIRED,
            ],
            Product::PARAM_SHOP . '.' . Shop::PARAM_ID => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_INTEGER,
                RequestInterface::RULE_MIN_DIGITS . ':1',
                ...$this->addRuleIfProductIdEmpty(Rule::exists(Shop::TABLE_NAME, Shop::PARAM_ID)),
            ],
            ...$this->getCommonRules(),
        ];
    }
}
