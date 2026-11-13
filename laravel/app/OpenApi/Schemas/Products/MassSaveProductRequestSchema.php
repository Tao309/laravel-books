<?php

namespace App\OpenApi\Schemas\Products;

use App\Models\Product;
use App\OpenApi\Refs;
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
                    new OA\Schema(ref: Refs::STORE_PRODUCT_REQUEST),
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
class MassSaveProductRequestSchema {}
