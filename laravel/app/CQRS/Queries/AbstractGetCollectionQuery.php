<?php

namespace App\CQRS\Queries;

use App\DTOs\CollectionQueryDtoInterface;

abstract class AbstractGetCollectionQuery implements GetCollectionQueryInterface
{
    public function __construct(
        public CollectionQueryDtoInterface $dto,
    ) {}

    public function getDTO(): CollectionQueryDtoInterface
    {
        return $this->dto;
    }
}
