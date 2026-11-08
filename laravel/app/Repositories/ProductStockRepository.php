<?php

namespace App\Repositories;

use App\DTOs\ProductStockDTO;
use App\Models\ProductStock;
use Illuminate\Support\Facades\Auth;

class ProductStockRepository
{
    public function massCreate(int $relEntityId, array $stocksData): bool
    {
        foreach ($stocksData as $stockData) {
            $dto = ProductStockDTO::fromArray($stockData);

            ProductStock::create(array_merge([
                ProductStock::PARAM_PRODUCT_ID => $relEntityId,
                ProductStock::PARAM_USER_ID => Auth::id(),
            ], $dto->getEntityData()));
        }

        return true;
    }

    public function massUpdate(int $relEntityId, array $stocksData): bool
    {
        foreach ($stocksData as $stockData) {
            $dto = ProductStockDTO::fromArray($stockData);

            ProductStock::where([
                ProductStock::PARAM_PRODUCT_ID => $relEntityId,
                ProductStock::PARAM_USER_ID => Auth::id(),
            ])->update($dto->getEntityData());
        }

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
