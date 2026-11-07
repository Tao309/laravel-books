<?php

namespace App\DTOs;

use App\Models\BookUserData;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class BookUserDataDTO implements UserDataDtoInterface
{
    public function __construct(
        public int $bookId,
        public ?string $releaseDate,
        public ?int $listenPriceValue,
        public ?string $comment,
    ) {}

    public static function fromArray(array $data): UserDataDtoInterface
    {
        return new self(
            bookId: $data[BookUserData::PARAM_BOOK_ID],
            releaseDate: Arr::get($data, BookUserData::PARAM_RELEASE_DATE) ?? null,
            listenPriceValue: Arr::get($data, BookUserData::PARAM_LISTEN_PRICE_VALUE) ?? null,
            comment: Arr::get($data, BookUserData::PARAM_COMMENT) ?? null,
        );
    }

    public function getEntityData(): array
    {
        $releaseDate = $this->releaseDate ? Carbon::createFromFormat('Y.m.d', $this->releaseDate) : null;

        return [
            BookUserData::PARAM_RELEASE_DATE => $releaseDate,
            BookUserData::PARAM_LISTEN_PRICE_VALUE => $this->listenPriceValue,
            BookUserData::PARAM_COMMENT => $this->comment,
        ];
    }
}
