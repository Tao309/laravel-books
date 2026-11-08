<?php

namespace App\Repositories;

use App\DTOs\UserDataDtoInterface;
use App\Models\BookUserData;
use App\Repositories\Interfaces\UserDataRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class BookUserDataRepository implements UserDataRepositoryInterface
{
    public function get(int $relEntityId): BookUserData
    {
        return BookUserData::where([
            BookUserData::PARAM_BOOK_ID => $relEntityId,
            BookUserData::PARAM_USER_ID => Auth::id(),
        ])->firstOrFail();
    }

    public function create(int $relEntityId, UserDataDtoInterface $dto): BookUserData
    {
        return BookUserData::create(array_merge([
            BookUserData::PARAM_BOOK_ID => $relEntityId,
            BookUserData::PARAM_USER_ID => Auth::id(),
        ], $dto->getEntityData()));
    }

    public function update(int $relEntityId, UserDataDtoInterface $dto): int
    {
        return BookUserData::where([
            BookUserData::PARAM_BOOK_ID => $relEntityId,
            BookUserData::PARAM_USER_ID => Auth::id(),
        ])->update($dto->getEntityData());
    }

    public function delete(int $relEntityId): void
    {
        BookUserData::where([
            BookUserData::PARAM_BOOK_ID => $relEntityId,
            BookUserData::PARAM_USER_ID => Auth::id(),
        ])->delete();
    }
}
