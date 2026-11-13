<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Queries\SourceProducts\GetSourceProductsQuery;
use App\DTOs\CollectionQueryDtoInterface;

readonly class SearchSourceProductsService
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function searchByValue(CollectionQueryDtoInterface $dto)
    {
        return $this->bus->handle(new GetSourceProductsQuery($dto));
    }
}
