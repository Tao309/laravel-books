<?php

namespace App\DTOs;

use App\Http\Requests\IndexRequest;
use Arr;

class CollectionQueryDto implements CollectionQueryDtoInterface
{
    public function __construct(
        private ?string $search = null,
        private ?int $limit = 30,
        private ?array $ids = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            search: Arr::get($data, IndexRequest::FIELD_SEARCH),
            limit: Arr::get($data, IndexRequest::FIELD_LIMIT),
            ids: ! empty($data['id']) && is_array($data['id']) ? $data['id'] : null,
        );
    }

    public function hasFilters(): bool
    {
        return $this->getSearch() || $this->getLimit() || $this->getIds();
    }

    public function getSearch(): ?string
    {
        return $this->search ?? null;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getIds(): ?array
    {
        return $this->ids ?? null;
    }
}
