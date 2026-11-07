<?php

namespace App\DTOs;

use App\Models\ProductUserData;
use App\Services\DateService;
use Illuminate\Support\Arr;

class ProductUserDataDTO implements UserDataDtoInterface
{
    public function __construct(
        public int $productId,
        public bool $available,
        public bool $isArchive,
        public ?string $notAvailableDateFrom,
        public ?string $availableDateFrom,
        public ?int $listenPriceValue,
        public ?int $listenQtyValue,
        public ?string $releaseDate,
        public ?string $comment,
    ) {}

    public static function fromArray(array $data): UserDataDtoInterface
    {
        return new self(
            productId: $data[ProductUserData::PARAM_PRODUCT_ID],
            available: Arr::get($data, ProductUserData::PARAM_AVAILABLE) ?? true,
            isArchive: Arr::get($data, ProductUserData::PARAM_IS_ARCHIVE) ?? false,
            notAvailableDateFrom: Arr::get($data, ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM) ?? null,
            availableDateFrom: Arr::get($data, ProductUserData::PARAM_AVAILABLE_DATE_FROM) ?? null,
            listenPriceValue: Arr::get($data, ProductUserData::PARAM_LISTEN_PRICE_VALUE) ?? null,
            listenQtyValue: Arr::get($data, ProductUserData::PARAM_LISTEN_QTY_VALUE) ?? null,
            releaseDate: Arr::get($data, ProductUserData::PARAM_RELEASE_DATE) ?? null,
            comment: Arr::get($data, ProductUserData::PARAM_COMMENT) ?? null,
        );
    }

    public function getEntityData(): array
    {
        return [
            ProductUserData::PARAM_AVAILABLE => $this->available,
            ProductUserData::PARAM_IS_ARCHIVE => $this->isArchive,
            ProductUserData::PARAM_NOT_AVAILABLE_DATE_FROM => DateService::createFromDate($this->notAvailableDateFrom),
            ProductUserData::PARAM_AVAILABLE_DATE_FROM => DateService::createFromDate($this->availableDateFrom),
            ProductUserData::PARAM_LISTEN_PRICE_VALUE => $this->listenPriceValue,
            ProductUserData::PARAM_LISTEN_QTY_VALUE => $this->listenQtyValue,
            ProductUserData::PARAM_RELEASE_DATE => DateService::createFromDate($this->releaseDate),
            ProductUserData::PARAM_COMMENT => $this->comment,
        ];
    }
}
