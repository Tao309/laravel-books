<?php

namespace App\Repositories;

use App\Models\BookUserData;
use App\Repositories\Interfaces\UserDataRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class BookUserDataRepository implements UserDataRepositoryInterface
{
    public function get(int $relatedEntityId): BookUserData
    {
        return BookUserData::where([
            BookUserData::PARAM_BOOK_ID => $relatedEntityId,
            BookUserData::PARAM_USER_ID => Auth::id(),
        ])->firstOrFail();
    }

    public function create(int $relatedEntityId, array $data): BookUserData
    {
        $releaseDate = Arr::get($data, BookUserData::PARAM_RELEASE_DATE)
            ? Carbon::createFromFormat('Y.m.d', $data[BookUserData::PARAM_RELEASE_DATE])
            : null;

        return BookUserData::create([
            BookUserData::PARAM_BOOK_ID => $relatedEntityId,
            BookUserData::PARAM_USER_ID => Auth::id(),
            BookUserData::PARAM_RELEASE_DATE => $releaseDate,
            BookUserData::PARAM_LISTEN_PRICE_VALUE => Arr::get($data, BookUserData::PARAM_LISTEN_PRICE_VALUE) ?? null,
            BookUserData::PARAM_COMMENT => Arr::get($data, BookUserData::PARAM_COMMENT) ?? null,
        ]);
    }

    public function update(int $relatedEntityId, array $data): int
    {
        $releaseDate = Arr::get($data, BookUserData::PARAM_RELEASE_DATE)
            ? Carbon::createFromFormat('Y.m.d', $data[BookUserData::PARAM_RELEASE_DATE])
            : null;

        return BookUserData::where([
            BookUserData::PARAM_BOOK_ID => $relatedEntityId,
            BookUserData::PARAM_USER_ID => Auth::id(),
        ])->update([
            BookUserData::PARAM_RELEASE_DATE => $releaseDate,
            BookUserData::PARAM_LISTEN_PRICE_VALUE => Arr::get($data, BookUserData::PARAM_LISTEN_PRICE_VALUE) ?? null,
            BookUserData::PARAM_COMMENT => Arr::get($data, BookUserData::PARAM_COMMENT) ?? null,
        ]);
    }

    public function delete(int $relatedEntityId): void
    {
        BookUserData::where([
            BookUserData::PARAM_BOOK_ID => $relatedEntityId,
        ])->delete();
    }
}
