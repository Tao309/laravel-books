<?php

namespace App\DTOs;

use App\Models\ProductStock;
use Illuminate\Support\Arr;

class ProductStockDTO implements UserDataDtoInterface
{
    public function __construct(
        public int $qty,
        public string $date,
        public ?string $log,
    ) {}

    public static function fromArray(array $data): UserDataDtoInterface
    {
        return new self(
            qty: Arr::get($data, ProductStock::PARAM_QTY),
            date: Arr::get($data, ProductStock::PARAM_DATE),
            log: Arr::get($data, ProductStock::PARAM_LOG),
        );
    }

    public function getEntityData(): array
    {
        return [
            ProductStock::PARAM_QTY => $this->qty,
            ProductStock::PARAM_DATE => $this->date,
            ProductStock::PARAM_LOG => $this->log,
        ];
    }
}
