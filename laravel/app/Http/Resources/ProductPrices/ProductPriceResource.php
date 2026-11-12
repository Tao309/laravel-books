<?php

namespace App\Http\Resources\ProductPrices;

use App\Http\Resources\AbstractResource;
use App\Models\ProductPrice;
use App\Services\DateService;
use Illuminate\Http\Request;

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
