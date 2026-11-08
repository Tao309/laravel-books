<?php

namespace App\Http\Resources\ProductPrices;

use App\Http\Resources\AbstractResource;
use App\Models\ProductPrice;
use App\Services\DateService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductPriceResource',
    properties: [
        new OA\Property(property: ProductPrice::PARAM_PRICE, type: 'int', example: 500),
        new OA\Property(property: ProductPrice::PARAM_DATE, type: 'string', example: '2026-09-10 10:10:19'),
    ],
    type: 'object'
)]
class ProductPriceResource extends AbstractResource
{
    public function getArrayData(Request $request): array
    {
        return [
            ProductPrice::PARAM_PRICE => $this->offsetGet(ProductPrice::PARAM_PRICE),
            ProductPrice::PARAM_DATE => DateService::createFromDate($this->offsetGet(ProductPrice::PARAM_DATE)),
        ];
    }
}
