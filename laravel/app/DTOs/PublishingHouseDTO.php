<?php

namespace App\DTOs;

use App\Models\PublishingHouse;

class PublishingHouseDTO
{
    public function __construct(
        public string $name,
    ) {}

    public static function fromArray(array $data): PublishingHouseDTO
    {
        return new self(
            name: $data['name'],
        );
    }

    public function toArray(): array
    {
        return [
            PublishingHouse::NAME => $this->name,
        ];
    }
}
