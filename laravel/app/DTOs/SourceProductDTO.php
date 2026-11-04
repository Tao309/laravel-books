<?php

namespace App\DTOs;

use App\Models\SourceProduct;
use Illuminate\Support\Arr;

class SourceProductDTO implements DtoInterface
{
    public function __construct(
        public string $title,
        public array $sourceProductType,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data[SourceProduct::PARAM_TITLE],
            sourceProductType: $data[SourceProduct::PARAM_SOURCE_PRODUCT_TYPE],
        );
    }

    public function toArray(): array
    {
        return [
            SourceProduct::PARAM_TITLE => $this->title,
            // > relations
            SourceProduct::PARAM_SOURCE_PRODUCT_TYPE => Arr::get($this->sourceProductType, 'id'),
            // < relations
        ];
    }
}
