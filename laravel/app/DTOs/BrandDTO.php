<?php

namespace App\DTOs;

use App\Models\Brand;
use Illuminate\Support\Arr;

class BrandDTO extends AbstractDTO
{
    public function __construct(
        public string $code,
        public string $title,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data[Brand::PARAM_CODE],
            title: Arr::get($data, Brand::PARAM_TITLE, []),
        );
    }

    public function getEntityData(): array
    {
        return [
            Brand::PARAM_CODE => $this->code,
            Brand::PARAM_TITLE => $this->title,
        ];
    }

    public function getRelationsData(): array
    {
        return [];
    }
}
