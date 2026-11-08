<?php

namespace App\Http\Resources\ProductStocks;

use App\Http\Resources\AbstractResource;
use App\Models\ProductStock;
use App\Services\DateService;
use Illuminate\Http\Request;
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
class ProductStockResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            ProductStock::PARAM_QTY => $this->offsetGet(ProductStock::PARAM_QTY),
            ProductStock::PARAM_DATE => DateService::createFromDate($this->offsetGet(ProductStock::PARAM_DATE)),
            ProductStock::PARAM_LOG => $this->offsetGet(ProductStock::PARAM_LOG),
        ];
    }
}
