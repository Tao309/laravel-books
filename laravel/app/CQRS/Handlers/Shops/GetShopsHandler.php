<?php

namespace App\CQRS\Handlers\Shops;

use App\CQRS\Queries\Shops\GetShopsQuery;
use Illuminate\Database\Eloquent\Collection;

class GetShopsHandler extends AbstractShopHandler
{
    public function handle(GetShopsQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
