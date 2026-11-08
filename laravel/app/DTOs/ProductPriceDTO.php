<?php

namespace App\DTOs;

use App\Models\ProductPrice;
use Illuminate\Support\Arr;

class ProductPriceDTO implements UserDataDtoInterface
{
    public function __construct(
        public int $price,
        public string $date,
    ) {}

    public static function fromArray(array $data): UserDataDtoInterface
    {
        return new self(
            price: Arr::get($data, ProductPrice::PARAM_PRICE),
            date: Arr::get($data, ProductPrice::PARAM_DATE),
        );
    }

    public function getEntityData(): array
    {
        return [
            ProductPrice::PARAM_PRICE => $this->price,
            ProductPrice::PARAM_DATE => $this->date,
        ];
    }
}
