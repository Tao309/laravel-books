<?php

namespace App\DTOs;

use App\Models\SourceProduct;
use Illuminate\Support\Arr;

class SourceProductDTO extends AbstractDTO
{
    public function __construct(
        public string $title,
        public array $sourceProductType,
        public array $sourceProductUserData,
        public array $brand,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data[SourceProduct::PARAM_TITLE],
            sourceProductType: $data[SourceProduct::PARAM_SOURCE_PRODUCT_TYPE],
            sourceProductUserData: Arr::get($data, SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA, []),
            brand: Arr::get($data, SourceProduct::PARAM_BRAND, []),
        );
    }

    public function getEntityData(): array
    {
        return [
            SourceProduct::PARAM_TITLE => $this->title,
        ];
    }

    public function getRelationsData(): array
    {
        return [
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => Arr::get($this->sourceProductType, 'id'),
            SourceProduct::PARAM_SOURCE_PRODUCT_USER_DATA => $this->sourceProductUserData,
            SourceProduct::PARAM_BRAND => Arr::get($this->brand, 'id'),
        ];
    }
}
