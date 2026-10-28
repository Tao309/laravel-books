<?php

namespace App\DTOs;

use App\Models\PublishingBrand;

class PublishingBrandDTO implements DtoInterface
{
    public function __construct(
        public string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data[PublishingBrand::NAME],
        );
    }

    public function toArray(): array
    {
        return [
            PublishingBrand::NAME => $this->name,
        ];
    }
}
