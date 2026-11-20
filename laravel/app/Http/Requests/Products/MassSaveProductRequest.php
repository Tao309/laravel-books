<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\RequestInterface;
use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;

class MassSaveProductRequest extends AbstractProductRequest
{
    public function messages(): array
    {
        return [
            'items.*.' . Product::PARAM_SHOP_PRODUCT_ID . '.unique' => 'A Product with this ShopProductId and Shop already exists',
            'items.*.' . Product::PARAM_SHOP . '.unique' => 'A Product with this ShopProductId and Shop already exists',
            'items.*.' . Product::PARAM_BOOK . '.required_without' => 'Book or SourceProduct is required',
            'items.*.' . Product::PARAM_SOURCE_PRODUCT . '.required_without' => 'Book or SourceProduct is required',
        ];
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'items' => [
                RequestInterface::RULE_REQUIRED,
                RequestInterface::RULE_ARRAY,
                RequestInterface::RULE_MIN . ':1',
                RequestInterface::RULE_MAX . ':200',
            ],
        ];

        foreach ($this->input('items', []) as $index => $productData) {
            $request = new StoreProductRequest;

            if (! empty($productData[Product::PARAM_ID])) {
                $request = new UpdateProductRequest;
                $request->productId = $productData[Product::PARAM_ID];
            }

            $request->merge($productData);

            foreach ($request->rules() as $field => $fieldRules) {
                if (in_array($field, [Product::PARAM_ID, Product::PARAM_SHOP_PRODUCT_ID])) {
                    continue;
                }

                $rules['items.' . $index . '.' . $field] = $fieldRules;
            }

            $rules['items.' . $index . '.' . Product::PARAM_ID] = [
                RequestInterface::RULE_INTEGER,
                RequestInterface::RULE_MIN_DIGITS . ':1',
                RequestInterface::RULE_REQUIRED_WITHOUT . ':items.' . $index . '.' . Product::PARAM_SHOP_PRODUCT_ID,
            ];
            $rules['items.' . $index . '.' . Product::PARAM_SHOP_PRODUCT_ID] = [
                RequestInterface::RULE_STRING,
                RequestInterface::RULE_MIN . ':5',
                RequestInterface::RULE_MAX . ':30',
                RequestInterface::RULE_REQUIRED_WITHOUT . ':items.' . $index . '.' . Product::PARAM_ID,
            ];
        }

        return $rules;
    }
}
