<?php

namespace App\Http\Requests\Products;

use App\Models\Product;
use Illuminate\Contracts\Validation\ValidationRule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MassSaveProductRequest',
    properties: [
        new OA\Property(
            property: 'items',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: Product::PARAM_ID, type: 'integer', example: 1),
                    new OA\Property(property: Product::PARAM_SHOP_PRODUCT_ID, type: 'integer', example: '2624422'),
                ],
                type: 'object',
                allOf: [
                    new OA\Schema(ref: '#/components/schemas/StoreProductRequest'),
                ],
                anyOf: [
                    new OA\Schema(required: [Product::PARAM_ID]),
                    new OA\Schema(required: [Product::PARAM_SHOP_PRODUCT_ID]),
                ]
            )
        ),
    ],
    type: 'object',
)]
class MassSaveProductRequest extends AbstractProductRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'items' => [
                'required',
                'array',
                'min:1',
                'max:200',
            ],
            'items.*.'.Product::PARAM_ID => [
                'required',
                'integer',
                'min_digits:1',
                'required_without:items.*.'.Product::PARAM_SHOP_PRODUCT_ID,
            ],
            'items.*.'.Product::PARAM_SHOP_PRODUCT_ID => [
                'required',
                'string',
                'min:5',
                'max:30',
                'required_without:items.*.'.Product::PARAM_ID,
            ],
        ];

        foreach ($this->input('items', []) as $index => $product) {
            $request = new StoreProductRequest;

            if (! empty($product[Product::PARAM_ID])) {
                $request = new UpdateProductRequest;
                $request->productId = $product[Product::PARAM_ID];
            }

            foreach ($request->rules() as $field => $fieldRules) {
                if (in_array($field, [Product::PARAM_ID, Product::PARAM_SHOP_PRODUCT_ID])) {
                    continue;
                }

                $rules["items.$index.$field"] = $fieldRules;
            }
        }

        return $rules;
    }
}
