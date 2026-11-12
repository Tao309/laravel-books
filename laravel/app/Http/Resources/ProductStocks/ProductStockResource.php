<?php

namespace App\Http\Resources\ProductStocks;

use App\Http\Resources\AbstractResource;
use App\Models\ProductStock;
use App\Services\DateService;
use Illuminate\Http\Request;

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
