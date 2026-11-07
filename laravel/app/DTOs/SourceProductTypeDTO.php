<?php

namespace App\DTOs;

use App\Models\SourceProductType;

class SourceProductTypeDTO extends AbstractDTO
{
    public function __construct(
        public string $code,
        public string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data[SourceProductType::PARAM_CODE],
            name: $data[SourceProductType::PARAM_NAME],
        );
    }

    public function getEntityData(): array
    {
        return [
            SourceProductType::PARAM_CODE => $this->code,
            SourceProductType::PARAM_NAME => $this->name,
        ];
    }

    public function getRelationsData(): array
    {
        return [];
    }
}
