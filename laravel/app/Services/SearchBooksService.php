<?php

namespace App\Services;

use App\CQRS\CommandBus;
use App\CQRS\Queries\Books\GetBooksQuery;
use App\DTOs\CollectionQueryDtoInterface;

readonly class SearchBooksService
{
    public function __construct(
        private CommandBus $bus,
    ) {}

    public function searchByValue(CollectionQueryDtoInterface $dto)
    {
        return $this->bus->dispatch(new GetBooksQuery($dto));
    }
}
