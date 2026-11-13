<?php

namespace App\OpenApi\Schemas\Products;

use App\Models\Product;
use App\Models\ProductUserData;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ArchiveProductRequest',
    properties: [
        new OA\Property(
            property: Product::PARAM_SHOP_PRODUCT_ID,
            type: 'string',
            example: '3315320967'
        ),
        new OA\Property(
            property: ProductUserData::PARAM_IS_ARCHIVE,
            type: 'boolean',
            example: true,
        ),
    ],
    type: 'object',
)]
class ArchiveProductRequestSchema {}
