<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Queries\Brands\GetBrandsQuery;
use App\DTOs\CollectionQueryDtoInterface;

readonly class SearchBrandsService
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function searchByValue(CollectionQueryDtoInterface $dto)
    {
        return $this->bus->handle(new GetBrandsQuery($dto));
    }
}
