<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\RequestInterface;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':5',
                RequestInterface::RULE_MAX . ':30',
                ...$this->addRuleIfProductIdEmpty(
                    Rule::unique(Product::TABLE_NAME)->where(function ($query) {
                        $shopId = $this->shop['id'] ?? null;

                        if ($shopId === null) {
                            throw ValidationException::withMessages([
                                'shop.id' => __('validation.required', [
                                    'attribute' => __('validation.attributes.shop.id'),
                                ]),
                            ]);
                        }

                        return $query->where(Product::PARAM_SHOP_ID, $shopId);
                    })->ignore($id)
                ),
            ],
            Product::PARAM_SHOP => [
                'required',
                ...$this->addRuleIfProductIdEmpty(Rule::exists(Shop::TABLE_NAME, Shop::PARAM_ID)),
                ...$this->addRuleIfProductIdEmpty(
                    Rule::unique(Product::TABLE_NAME, Product::PARAM_SHOP_ID)->where(function ($query) {
                        $shopProductId = $this->shop_product_id ?? null;

                        if ($shopProductId === null) {
                            throw ValidationException::withMessages([
                                Product::PARAM_SHOP_PRODUCT_ID => __('validation.required', [
                                    'attribute' => __('validation.attributes.' . Product::PARAM_SHOP_PRODUCT_ID),
                                ]),
                            ]);
                        }

                        return $query->where(Product::PARAM_SHOP_PRODUCT_ID, $shopProductId);
                    })->ignore($id)
                ),
            ],
            ...$this->getCommonRules(),
        ];
    }
}
