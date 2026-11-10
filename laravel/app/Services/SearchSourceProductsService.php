<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Queries\SourceProducts\GetSourceProductsQuery;
use App\DTOs\CollectionQueryDtoInterface;

readonly class SearchSourceProductsService
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function searchByValue(CollectionQueryDtoInterface $dto)
    {
        return $this->bus->dispatch(new GetSourceProductsQuery($dto));
    }
}
