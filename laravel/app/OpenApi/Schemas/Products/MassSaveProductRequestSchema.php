<?php

namespace App\OpenApi\Schemas\Products;

use App\Models\Product;
use App\OpenApi\Refs;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            items: new OA\Items(
                allOf: [
                    new OA\Schema(ref: Refs::STORE_PRODUCT_REQUEST),
                ],
                anyOf: [
                    new OA\Schema(required: [Product::PARAM_ID]),
                    new OA\Schema(required: [Product::PARAM_SHOP_PRODUCT_ID]),
                ],
                properties: [
                    new OA\Property(example: 1, property: Product::PARAM_ID, type: 'integer'),
                    new OA\Property(example: '2624422', property: Product::PARAM_SHOP_PRODUCT_ID, type: 'integer'),
                ],
                type: 'object'
            ),
            property: 'items',
            type: 'array'
        ),
    ],
    schema: 'MassSaveProductRequest',
    type: 'object',
)]
class MassSaveProductRequestSchema {}
