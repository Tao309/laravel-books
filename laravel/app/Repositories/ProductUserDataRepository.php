<?php

namespace App\Repositories;

use App\DTOs\UserDataDtoInterface;
use App\Models\ProductUserData;
use App\Repositories\Interfaces\UserDataRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ProductUserDataRepository implements UserDataRepositoryInterface
{
    public function get(int $relEntityId): ProductUserData
    {
        return ProductUserData::where([
            ProductUserData::PARAM_PRODUCT_ID => $relEntityId,
            ProductUserData::PARAM_USER_ID => Auth::id(),
        ])->firstOrFail();
    }

    public function create(int $relEntityId, UserDataDtoInterface $dto): ProductUserData
    {
        return ProductUserData::create(array_merge([
            ProductUserData::PARAM_PRODUCT_ID => $relEntityId,
            ProductUserData::PARAM_USER_ID => Auth::id(),
        ], $dto->getEntityData()));
    }

    public function update(int $relEntityId, UserDataDtoInterface $dto): int
    {
        return ProductUserData::where([
            ProductUserData::PARAM_PRODUCT_ID => $relEntityId,
            ProductUserData::PARAM_USER_ID => Auth::id(),
        ])->update($dto->getEntityData());
    }

    public function delete(int $relEntityId): void
    {
        ProductUserData::where([
            ProductUserData::PARAM_PRODUCT_ID => $relEntityId,
            ProductUserData::PARAM_USER_ID => Auth::id(),
        ])->delete();
    }
}
