<?php

namespace App\DTOs;

use App\Models\PublishingHouse;

class PublishingHouseDTO extends AbstractDTO
{
    public function __construct(
        public string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data[PublishingHouse::NAME],
        );
    }

    public function getEntityData(): array
    {
        return [
            PublishingHouse::NAME => $this->name,
        ];
    }

    public function getRelationsData(): array
    {
        return [];
    }
}
