<?php

namespace App\OpenApi\Schemas\Products;

use App\Models\Product;
use App\OpenApi\Refs;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 1, property: Product::PARAM_ID, type: 'integer'),
        new OA\Property(example: '3315320967', property: Product::PARAM_SHOP_PRODUCT_ID, type: 'integer'),
        new OA\Property(example: '1903147507', property: Product::PARAM_SHOP_PRODUCT_CODE, type: 'integer'),
        new OA\Property(example: 'Хтонь за околицей. Книга первая', property: Product::PARAM_TITLE, type: 'integer'),
        new OA\Property(example: '2026-09-10 10:10:19', property: Model::UPDATED_AT, type: 'string'),
        new OA\Property(example: '2026-09-10 10:10:19', property: Model::CREATED_AT, type: 'string'),
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
            items: new OA\Items(
                ref: Refs::PRODUCT_PRICE_RESOURCE,
                type: 'object'
            ),
            property: Product::PARAM_PRICES,
            type: 'array'
        ),
        new OA\Property(
            items: new OA\Items(
                ref: Refs::PRODUCT_STOCK_RESOURCE,
                type: 'object'
            ),
            property: Product::PARAM_STOCKS,
            type: 'array'
        ),
        // < relations
    ],
    schema: 'ProductResource',
    type: 'object'
)]
#[OA\Schema(
    properties: [
        new OA\Property(
            property: 'data',
            ref: Refs::PRODUCT_RESOURCE,
            type: 'object'
        ),
        new OA\Property(example: true, property: 'success', type: 'bool'),
    ],
    schema: 'ProductDataResource',
    type: 'object'
)]
class ProductResourceSchema {}
