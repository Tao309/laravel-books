<?php

namespace App\Repositories;

use App\DTOs\ProductPriceDTO;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\Auth;

class ProductPriceRepository
{
    public function massCreate(int $relEntityId, array $pricesData): bool
    {
        foreach ($pricesData as $priceData) {
            $dto = ProductPriceDTO::fromArray($priceData);

            ProductPrice::create(array_merge([
                ProductPrice::PARAM_PRODUCT_ID => $relEntityId,
                ProductPrice::PARAM_USER_ID => Auth::id(),
            ], $dto->getEntityData()));
        }

        return true;
    }

    public function massUpdate(int $relEntityId, array $pricesData): bool
    {
        foreach ($pricesData as $priceData) {
            $dto = ProductPriceDTO::fromArray($priceData);

            ProductPrice::where([
                ProductPrice::PARAM_PRODUCT_ID => $relEntityId,
                ProductPrice::PARAM_USER_ID => Auth::id(),
            ])->update($dto->getEntityData());
        }

        return true;
    }

    public function delete(int $relEntityId): void
    {
        ProductPrice::where([
            ProductPrice::PARAM_PRODUCT_ID => $relEntityId,
            ProductPrice::PARAM_USER_ID => Auth::id(),
        ])->delete();
    }
}
