<?php

namespace App\OpenApi\Schemas\Products;

use App\Models\Book;
use App\Models\Product;
use App\Models\Shop;
use App\Models\SourceProduct;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            example: '3315320967',
            property: Product::PARAM_SHOP_PRODUCT_ID,
            type: 'string'
        ),
        new OA\Property(
            example: '1903147507',
            property: Product::PARAM_SHOP_PRODUCT_CODE,
            type: 'string',
        ),
        new OA\Property(
            example: 'Хтонь за околицей. Книга первая',
            property: Product::PARAM_TITLE,
            type: 'string',
        ),
        // > relations
        new OA\Property(
            properties: [
                new OA\Property(example: 2, property: Shop::PARAM_ID, type: 'integer'),
            ],
            property: Product::PARAM_SHOP,
            type: 'object'
        ),
        new OA\Property(
            properties: [
                new OA\Property(example: 4, property: Shop::PARAM_ID, type: 'integer'),
            ],
            property: Product::PARAM_CITY,
            type: 'object'
        ),
        new OA\Property(
            properties: [
                new OA\Property(example: 4, property: Book::PARAM_ID, type: 'integer'),
            ],
            property: Product::PARAM_BOOK,
            type: 'object'
        ),
        new OA\Property(
            properties: [
                new OA\Property(example: 4, property: SourceProduct::PARAM_ID, type: 'integer'),
            ],
            property: Product::PARAM_SOURCE_PRODUCT,
            type: 'object'
        ),
        // < relations
    ],
    required: [
        Product::PARAM_SHOP_PRODUCT_ID,
        Product::PARAM_SHOP,
        Product::PARAM_TITLE,
    ],
    schema: 'UpdateProductRequest',
    type: 'object'
)]
class UpdateProductRequestSchema {}
