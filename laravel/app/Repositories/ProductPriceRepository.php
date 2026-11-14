<?php

namespace App\Repositories;

use App\DTOs\ProductPriceDTO;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\Auth;

class ProductPriceRepository
{
    public function massSave(int $relEntityId, array $pricesData): bool
    {
        if (empty($pricesData)) {
            return true;
        }

        $userId = Auth::id();

        $rows = [];

        foreach ($pricesData as $priceData) {
            $dto = ProductPriceDTO::fromArray($priceData);

            $rows[] = array_merge(
                $dto->getEntityData(),
                [
                    ProductPrice::PARAM_PRODUCT_ID => $relEntityId,
                    ProductPrice::PARAM_USER_ID => $userId,
                ]
            );
        }

        ProductPrice::query()->upsert(
            $rows,
            [
                ProductPrice::PARAM_PRODUCT_ID,
                ProductPrice::PARAM_USER_ID,
                ProductPrice::PARAM_DATE,
            ],
            [ProductPrice::PARAM_PRICE]
        );

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
