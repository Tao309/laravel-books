<?php

namespace App\DTOs;

use App\Models\PaperType;

class PaperTypeDTO implements DtoInterface
{
    public function __construct(
        public string $label,
        public ?string $description,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            label: $data[PaperType::PARAM_LABEL],
            description: $data[PaperType::PARAM_DESCRIPTION],
        );
    }

    public function toArray(): array
    {
        return [
            PaperType::PARAM_LABEL => $this->label,
            PaperType::PARAM_DESCRIPTION => $this->description,
        ];
    }
}
