<?php

namespace App\Repositories;

use App\DTOs\UserDataDtoInterface;
use App\Models\SourceProductUserData;
use App\Repositories\Interfaces\UserDataRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class SourceProductUserDataRepository implements UserDataRepositoryInterface
{
    public function get(int $relEntityId): SourceProductUserData
    {
        return SourceProductUserData::where([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $relEntityId,
            SourceProductUserData::PARAM_USER_ID => Auth::id(),
        ])->firstOrFail();
    }

    public function create(int $relEntityId, UserDataDtoInterface $dto): SourceProductUserData
    {
        return SourceProductUserData::create(array_merge([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $relEntityId,
            SourceProductUserData::PARAM_USER_ID => Auth::id(),
        ], $dto->getEntityData()));
    }

    public function update(int $relEntityId, UserDataDtoInterface $dto): int
    {
        return SourceProductUserData::where([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $relEntityId,
            SourceProductUserData::PARAM_USER_ID => Auth::id(),
        ])->update($dto->getEntityData());
    }

    public function delete(int $relEntityId): void
    {
        SourceProductUserData::where([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $relEntityId,
        ])->delete();
    }
}
