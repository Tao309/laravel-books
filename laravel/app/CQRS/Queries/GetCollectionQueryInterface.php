<?php

namespace App\CQRS\Queries;

interface GetCollectionQueryInterface
{
    public ?string $search {
        get;
    }

    public int $limit {
        get;
    }

    public function toArray(): array;
}
