<?php

namespace App\CQRS\Queries;

use App\DTOs\CollectionQueryDto;

interface GetCollectionQueryInterface
{
    //    public ?string $search {
    //        get;
    //    }
    //
    //    public int $limit {
    //        get;
    //    }
    //
    //    public array $ids {
    //        get;
    //    }
    //
    public function getDTO(): CollectionQueryDto;
}
