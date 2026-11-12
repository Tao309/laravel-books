<?php

namespace App\OpenApi\Schemas\ProductPrices;

use App\Models\ProductPrice;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 500, property: ProductPrice::PARAM_PRICE, type: 'int'),
        new OA\Property(example: '2026-09-10 10:10:19', property: ProductPrice::PARAM_DATE, type: 'string'),
    ],
    schema: 'ProductPriceResource',
    type: 'object'
)]
class ProductPriceResourceSchema {}
