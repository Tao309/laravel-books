<?php

namespace App\Services;

use App\CQRS\Bus;
use App\CQRS\Queries\Authors\GetAuthorsPaginateQuery;
use App\DTOs\CollectionQueryDtoInterface;

readonly class SearchAuthorsService
{
    public function __construct(
        private Bus $bus,
    ) {}

    public function searchByValue(CollectionQueryDtoInterface $dto)
    {
        return $this->bus->handle(new GetAuthorsPaginateQuery($dto));
    }
}
