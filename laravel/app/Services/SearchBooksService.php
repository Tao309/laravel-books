<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Queries\Books\GetBooksQuery;
use App\DTOs\CollectionQueryDtoInterface;

readonly class SearchBooksService
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function searchByValue(CollectionQueryDtoInterface $dto)
    {
        return $this->bus->handle(new GetBooksQuery($dto));
    }
}
