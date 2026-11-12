<?php

namespace App\OpenApi\Schemas\Products;

use App\Models\Product;
use App\Models\ProductUserData;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(
            example: '3315320967',
            property: Product::PARAM_SHOP_PRODUCT_ID,
            type: 'string'
        ),
        new OA\Property(
            example: true,
            property: ProductUserData::PARAM_IS_ARCHIVE,
            type: 'boolean',
        ),
    ],
    schema: 'ArchiveProductRequest',
    type: 'object',
)]
class ArchiveProductRequestSchema {}
