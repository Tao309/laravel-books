<?php

namespace App\DTOs;

use App\Models\ProductStock;
use App\Services\DateService;
use Illuminate\Support\Arr;

class ProductStockDTO implements UserDataDtoInterface
{
    public function __construct(
        public int $qty,
        public string $date,
        public ?array $log,
    ) {}

    public static function fromArray(array $data): UserDataDtoInterface
    {
        return new self(
            qty: $data[ProductStock::PARAM_QTY],
            date: $data[ProductStock::PARAM_DATE],
            log: Arr::get($data, ProductStock::PARAM_LOG),
        );
    }

    public function getEntityData(): array
    {
        return [
            ProductStock::PARAM_QTY => $this->qty,
            ProductStock::PARAM_DATE => DateService::createFromDate($this->date),
            ProductStock::PARAM_LOG => $this->log ? json_encode($this->log, JSON_THROW_ON_ERROR) : null,
        ];
    }
}
