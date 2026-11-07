<?php

namespace App\DTOs;

use App\Models\BindingType;

class BindingTyperDTO extends AbstractDTO
{
    public function __construct(
        public string $label,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            label: $data[BindingType::PARAM_LABEL],
        );
    }

    public function getEntityData(): array
    {
        return [
            BindingType::PARAM_LABEL => $this->label,
        ];
    }

    public function getRelationsData(): array
    {
        return [];
    }
}
