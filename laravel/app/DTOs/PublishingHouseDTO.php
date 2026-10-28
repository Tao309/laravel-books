<?php

namespace App\DTOs;

use App\Models\PublishingHouse;

class PublishingHouseDTO implements DtoInterface
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

    public function toArray(): array
    {
        return [
            PublishingHouse::NAME => $this->name,
        ];
    }
}
