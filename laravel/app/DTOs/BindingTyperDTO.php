<?php

namespace App\DTOs;

use App\Models\BindingType;

class BindingTyperDTO implements DtoInterface
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

    public function toArray(): array
    {
        return [
            BindingType::PARAM_LABEL => $this->label,
        ];
    }
}
