<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Queries\Authors\GetAuthorsQuery;
use App\DTOs\CollectionQueryDtoInterface;

readonly class SearchAuthorsService
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function searchByValue(CollectionQueryDtoInterface $dto)
    {
        return $this->bus->dispatch(new GetAuthorsQuery($dto));
    }
}
