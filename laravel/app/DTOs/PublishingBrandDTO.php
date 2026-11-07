<?php

namespace App\DTOs;

use App\Models\PublishingBrand;

class PublishingBrandDTO extends AbstractDTO
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

    public function getEntityData(): array
    {
        return [
            PublishingBrand::NAME => $this->name,
        ];
    }

    public function getRelationsData(): array
    {
        return [];
    }
}
