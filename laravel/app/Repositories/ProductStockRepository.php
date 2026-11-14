<?php

namespace App\Repositories;

use App\DTOs\ProductStockDTO;
use App\Models\ProductStock;
use Illuminate\Support\Facades\Auth;

class ProductStockRepository
{
    public function massSave(int $relEntityId, array $stocksData): bool
    {
        if (empty($stocksData)) {
            return true;
        }

        $userId = Auth::id();

        $rows = [];

        foreach ($stocksData as $stockData) {
            $dto = ProductStockDTO::fromArray($stockData);

            $rows[] = array_merge(
                $dto->getEntityData(),
                [
                    ProductStock::PARAM_PRODUCT_ID => $relEntityId,
                    ProductStock::PARAM_USER_ID => $userId,
                ]
            );
        }

        ProductStock::query()->upsert(
            $rows,
            [
                ProductStock::PARAM_PRODUCT_ID,
                ProductStock::PARAM_USER_ID,
                ProductStock::PARAM_DATE,
            ],
            [ProductStock::PARAM_QTY]
        );

        return true;
    }

    public function delete(int $relEntityId): void
    {
        ProductStock::where([
            ProductStock::PARAM_PRODUCT_ID => $relEntityId,
            ProductStock::PARAM_USER_ID => Auth::id(),
        ])->delete();
    }
}
