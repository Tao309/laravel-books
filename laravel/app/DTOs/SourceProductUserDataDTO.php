<?php

namespace App\DTOs;

use App\Models\SourceProductUserData;
use Illuminate\Support\Arr;

class SourceProductUserDataDTO implements UserDataDtoInterface
{
    public function __construct(
        public int $sourceProductId,
        public ?int $listenPriceValue,
        public ?string $comment,
    ) {}

    public static function fromArray(array $data): UserDataDtoInterface
    {
        return new self(
            sourceProductId: $data[SourceProductUserData::PARAM_SOURCE_PRODUCT_ID],
            listenPriceValue: Arr::get($data, SourceProductUserData::PARAM_LISTEN_PRICE_VALUE) ?? null,
            comment: Arr::get($data, SourceProductUserData::PARAM_COMMENT) ?? null,
        );
    }

    public function getEntityData(): array
    {
        return [
            SourceProductUserData::PARAM_LISTEN_PRICE_VALUE => $this->listenPriceValue,
            SourceProductUserData::PARAM_COMMENT => $this->comment,
        ];
    }
}
