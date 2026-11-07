<?php

namespace App\DTOs;

use App\Models\PaperType;

class PaperTypeDTO extends AbstractDTO
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

    public function getEntityData(): array
    {
        return [
            PaperType::PARAM_LABEL => $this->label,
            PaperType::PARAM_DESCRIPTION => $this->description,
        ];
    }

    public function getRelationsData(): array
    {
        return [];
    }
}
