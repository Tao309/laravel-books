<?php

namespace App\Repositories;

use App\DTOs\ProductStockDTO;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use Illuminate\Support\Facades\Auth;

class ProductStockRepository
{
    public function massCreate(int $relEntityId, array $stocksData): bool
    {
        foreach ($stocksData as $stockData) {
            $dto = ProductStockDTO::fromArray($stockData);

            $this->create($relEntityId, $dto->getEntityData());
        }

        return true;
    }

    public function massUpdate(int $relEntityId, array $stocksData): bool
    {
        foreach ($stocksData as $stockData) {
            $dto = ProductStockDTO::fromArray($stockData);

            $model = ProductStock::where([
                ProductStock::PARAM_PRODUCT_ID => $relEntityId,
                ProductStock::PARAM_USER_ID => Auth::id(),
                ProductPrice::PARAM_DATE => $dto->getEntityData()[ProductStock::PARAM_DATE],
            ]);

            if ($model->exists()) {
                $model->update($dto->getEntityData());

                continue;
            }

            $this->create($relEntityId, $dto->getEntityData());
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

    private function create(int $relEntityId, array $data): ProductStock
    {
        return ProductStock::create(array_merge([
            ProductStock::PARAM_PRODUCT_ID => $relEntityId,
            ProductStock::PARAM_USER_ID => Auth::id(),
        ], $data));
    }
}
