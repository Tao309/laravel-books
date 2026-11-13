<?php

namespace App\OpenApi\Schemas\Products;

use App\Models\Book;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SourceProduct;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreProductRequest',
    required: [
        Product::PARAM_SHOP_PRODUCT_ID,
        Product::PARAM_SHOP,
        Product::PARAM_TITLE,
    ],
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
    type: 'object',
)]
class StoreProductRequestSchema {}
