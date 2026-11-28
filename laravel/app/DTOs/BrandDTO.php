<?php

namespace App\DTOs;

use App\Models\Brand;
use Illuminate\Support\Arr;

class BrandDTO extends AbstractDTO
{
    public function __construct(
        public string $title,
        public ?string $code,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: Arr::get($data, Brand::PARAM_TITLE),
            code: Arr::get($data, Brand::PARAM_CODE),
        );
    }

    public function getEntityData(): array
    {
        return [
            Brand::PARAM_TITLE => $this->title,
            Brand::PARAM_CODE => $this->code,
        ];
    }

    public function getRelationsData(): array
    {
        return [];
    }
}
