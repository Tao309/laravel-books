<?php

namespace App\CQRS\Queries;

use App\DTOs\CollectionQueryDtoInterface;

interface GetCollectionQueryInterface
{
    public function getDTO(): CollectionQueryDtoInterface;
}
