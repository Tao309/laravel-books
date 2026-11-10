<?php

namespace App\CQRS\Queries;

use App\DTOs\CollectionQueryDto;
use App\DTOs\CollectionQueryDtoInterface;

abstract class AbstractGetCollectionQuery implements GetCollectionQueryInterface
{
    public function __construct(
        public CollectionQueryDtoInterface $dto,
    ) {}

    public function getDTO(): CollectionQueryDto
    {
        return $this->dto;
    }
}
