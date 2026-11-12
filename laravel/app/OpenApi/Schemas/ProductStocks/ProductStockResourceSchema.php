<?php

namespace App\OpenApi\Schemas\ProductStocks;

use App\Models\ProductStock;
use OpenApi\Attributes as OA;

#[OA\Schema(
    properties: [
        new OA\Property(example: 10, property: ProductStock::PARAM_QTY, type: 'int'),
        new OA\Property(example: '2026-09-10 10:10:19', property: ProductStock::PARAM_DATE, type: 'string'),
        new OA\Property(example: '{json}', property: ProductStock::PARAM_LOG, type: 'string'),
    ],
    schema: 'ProductStockResource',
    type: 'object'
)]
class ProductStockResourceSchema {}
