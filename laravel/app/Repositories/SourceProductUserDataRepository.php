<?php

namespace App\Repositories;

use App\Models\SourceProductUserData;
use App\Repositories\Interfaces\UserDataRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class SourceProductUserDataRepository implements UserDataRepositoryInterface
{
    public function get(int $relatedEntityId): SourceProductUserData
    {
        return SourceProductUserData::where([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $relatedEntityId,
            SourceProductUserData::PARAM_USER_ID => Auth::id(),
        ])->firstOrFail();
    }

    public function create(int $relatedEntityId, array $data): SourceProductUserData
    {
        return SourceProductUserData::create([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $relatedEntityId,
            SourceProductUserData::PARAM_USER_ID => Auth::id(),
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => Arr::get($data, SourceProductUserData::PARAM_LISTEN_PRICE_VALUE) ?? null,
            SourceProductUserData::PARAM_COMMENT => Arr::get($data, SourceProductUserData::PARAM_COMMENT) ?? null,
        ]);
    }

    public function update(int $relatedEntityId, array $data): int
    {
        return SourceProductUserData::where([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $relatedEntityId,
            SourceProductUserData::PARAM_USER_ID => Auth::id(),
        ])->update([
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => Arr::get($data, SourceProductUserData::PARAM_LISTEN_PRICE_VALUE) ?? null,
            SourceProductUserData::PARAM_COMMENT => Arr::get($data, SourceProductUserData::PARAM_COMMENT) ?? null,
        ]);
    }

    public function delete(int $relatedEntityId): void
    {
        SourceProductUserData::where([
            SourceProductUserData::PARAM_SOURCE_PRODUCT_ID => $relatedEntityId,
        ])->delete();
    }
}
