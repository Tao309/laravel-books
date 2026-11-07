<?php

namespace App\DTOs;

use App\Models\BookSeries;

class BookSeriesDTO extends AbstractDTO
{
    public function __construct(
        public string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data[BookSeries::PARAM_NAME],
        );
    }

    public function getEntityData(): array
    {
        return [
            BookSeries::PARAM_NAME => $this->name,
        ];
    }

    public function getRelationsData(): array
    {
        return [];
    }
}
