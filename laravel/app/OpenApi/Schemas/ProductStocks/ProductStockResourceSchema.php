<?php

namespace App\OpenApi\Schemas\ProductStocks;

use App\Models\ProductStock;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductStockResource',
    properties: [
        new OA\Property(property: ProductStock::PARAM_QTY, type: 'int', example: 10),
        new OA\Property(property: ProductStock::PARAM_DATE, type: 'string', example: '2026-09-10 10:10:19'),
        new OA\Property(property: ProductStock::PARAM_LOG, type: 'string', example: '{json}'),
    ],
    type: 'object'
)]
class ProductStockResourceSchema {}
