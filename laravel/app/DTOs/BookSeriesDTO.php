<?php

namespace App\DTOs;

use App\Models\BookSeries;

class BookSeriesDTO implements DtoInterface
{
    public function __construct(
        public string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data[BookSeries::NAME],
        );
    }

    public function toArray(): array
    {
        return [
            BookSeries::NAME => $this->name,
        ];
    }
}
