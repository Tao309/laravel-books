<?php

namespace App\Http\Requests\Products;

use App\Models\Book;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SourceProduct;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateProductRequest',
    properties: [
        new OA\Property(
            property: Product::PARAM_SHOP_PRODUCT_ID,
            type: 'string',
            example: '3315320967'
        ),
        new OA\Property(
            property: Product::PARAM_SHOP_PRODUCT_CODE,
            type: 'string',
            example: '1903147507',
        ),
        new OA\Property(
            property: Product::PARAM_TITLE,
            type: 'string',
            example: 'Хтонь за околицей. Книга первая',
        ),
        // > relations
        new OA\Property(
            property: Product::PARAM_SHOP,
            properties: [
                new OA\Property(property: Shop::PARAM_ID, type: 'integer', example: 2),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: Product::PARAM_CITY,
            properties: [
                new OA\Property(property: Shop::PARAM_ID, type: 'integer', example: 4),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: Product::PARAM_BOOK,
            properties: [
                new OA\Property(property: Book::PARAM_ID, type: 'integer', example: 4),
            ],
            type: 'object'
        ),
        new OA\Property(
            property: Product::PARAM_SOURCE_PRODUCT,
            properties: [
                new OA\Property(property: SourceProduct::PARAM_ID, type: 'integer', example: 4),
            ],
            type: 'object'
        ),
        // < relations
    ],
    type: 'object'
)]
class UpdateProductRequest extends AbstractProductRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('product');

        return [
            Product::PARAM_SHOP_PRODUCT_ID => [
                'required',
                'string',
                'min:5',
                'max:30',
                Rule::unique(Product::TABLE_NAME)->where(function ($query) {
                    return $query->where(Product::PARAM_SHOP_ID, $this->shop['id']);
                })->ignore($id),
            ],
            Product::PARAM_SHOP => [
                'required',
                Rule::exists(Shop::TABLE_NAME, Shop::PARAM_ID),
                Rule::unique(Product::TABLE_NAME, Product::PARAM_SHOP_ID)->where(function ($query) {
                    return $query->where(Product::PARAM_SHOP_PRODUCT_ID, $this->shop_product_id);
                })->ignore($id),
            ],
            ...$this->getCommonRules(),
        ];
    }
}
