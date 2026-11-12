<?php

namespace App\Http\Requests\Products;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends AbstractProductRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->productId ?? $this->route('product');

        return [
            Product::PARAM_SHOP_PRODUCT_ID => [
                'required',
                'string',
                'min:5',
                'max:30',
                ...$this->addRuleIfProductIdEmpty(
                    Rule::unique(Product::TABLE_NAME)->where(function ($query) {
                        return $query->where(Product::PARAM_SHOP_ID, $this->shop['id']);
                    })->ignore($id)
                ),
            ],
            Product::PARAM_SHOP => [
                'required',
                ...$this->addRuleIfProductIdEmpty(Rule::exists(Shop::TABLE_NAME, Shop::PARAM_ID)),
                ...$this->addRuleIfProductIdEmpty(
                    Rule::unique(Product::TABLE_NAME, Product::PARAM_SHOP_ID)->where(function ($query) {
                        return $query->where(Product::PARAM_SHOP_PRODUCT_ID, $this->shop_product_id);
                    })->ignore($id)
                ),
            ],
            ...$this->getCommonRules(),
        ];
    }
}
