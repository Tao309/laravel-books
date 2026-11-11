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

            $this->create($relEntityId, $dto->getEntityData());
        }

        return true;
    }

    public function massUpdate(int $relEntityId, array $pricesData): bool
    {
        foreach ($pricesData as $priceData) {
            $dto = ProductPriceDTO::fromArray($priceData);

            $model = ProductPrice::where([
                ProductPrice::PARAM_PRODUCT_ID => $relEntityId,
                ProductPrice::PARAM_USER_ID => Auth::id(),
                ProductPrice::PARAM_DATE => $dto->getEntityData()[ProductPrice::PARAM_DATE],
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
        ProductPrice::where([
            ProductPrice::PARAM_PRODUCT_ID => $relEntityId,
            ProductPrice::PARAM_USER_ID => Auth::id(),
        ])->delete();
    }

    public function create(int $relEntityId, array $data): ProductPrice
    {
        return ProductPrice::create(array_merge([
            ProductPrice::PARAM_PRODUCT_ID => $relEntityId,
            ProductPrice::PARAM_USER_ID => Auth::id(),
        ], $data));
    }
}
