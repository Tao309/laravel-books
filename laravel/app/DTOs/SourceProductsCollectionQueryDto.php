<?php

namespace App\DTOs;

use App\CQRS\Queries\SourceProducts\SourceProductsCollectionQueryDtoInterface;

class SourceProductsCollectionQueryDto extends CollectionQueryDto implements SourceProductsCollectionQueryDtoInterface
{
    public const string FIELD_BRAND_CODE = 'brand_code';

    public function __construct(
        private ?string $brandCode = null,
        ?string $search = null,
        ?int $limit = 30,
        ?array $ids = []
    ) {
        parent::__construct($search, $limit, $ids);
    }

    protected static function getAdditionalData(array $data): array
    {
        return [
            'brandCode' => $data[self::FIELD_BRAND_CODE] ?? null,
        ];
    }

    public function hasFilters(): bool
    {
        return parent::hasFilters() || $this->brandCode !== null;
    }

    public function getBrandCode(): ?string
    {
        return $this->brandCode;
    }
}
