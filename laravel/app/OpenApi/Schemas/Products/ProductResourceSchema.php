<?php

namespace App\OpenApi\Schemas\Products;

use App\Models\Product;
use App\OpenApi\Refs;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductResource',
    properties: [
        new OA\Property(property: Product::PARAM_ID, type: 'integer', example: 1),
        new OA\Property(property: Product::PARAM_SHOP_PRODUCT_ID, type: 'integer', example: '3315320967'),
        new OA\Property(property: Product::PARAM_SHOP_PRODUCT_CODE, type: 'integer', example: '1903147507'),
        new OA\Property(property: Product::PARAM_TITLE, type: 'integer', example: 'Хтонь за околицей. Книга первая'),
        new OA\Property(property: Model::UPDATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: Model::CREATED_AT, type: 'string', example: '2026-09-10 10:10:19'),
        // > relations
        new OA\Property(
            property: Product::PARAM_SHOP,
            ref: Refs::SHOP_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: Product::PARAM_CITY,
            ref: Refs::CITY_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: Product::PARAM_BOOK,
            ref: Refs::BOOK_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: Product::PARAM_SOURCE_PRODUCT,
            ref: Refs::SOURCE_PRODUCT_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: Product::PARAM_PRODUCT_USER_DATA,
            ref: Refs::PRODUCT_USER_DATA_RESOURCE,
            type: 'object'
        ),
        new OA\Property(
            property: Product::PARAM_PRICES,
            type: 'array',
            items: new OA\Items(
                ref: Refs::PRODUCT_PRICE_RESOURCE,
                type: 'object'
            )
        ),
        new OA\Property(
            property: Product::PARAM_STOCKS,
            type: 'array',
            items: new OA\Items(
                ref: Refs::PRODUCT_STOCK_RESOURCE,
                type: 'object'
            )
        ),
        // < relations
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ProductDataResource',
    description: 'Single Product response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: Refs::PRODUCT_RESOURCE,
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
#[OA\Schema(
    schema: 'ProductDestroyResponse',
    description: 'Delete Product response',
    allOf: [
        new OA\Schema(ref: Refs::RESPONSE),
        new OA\Schema(
            properties: [
                new OA\Property(
                    property: 'data',
                    properties: [
                        new OA\Property(property: 'success', type: 'bool', example: true),
                    ],
                    type: 'object'
                ),
            ],
            type: 'object'
        ),
    ]
)]
class ProductResourceSchema {}
