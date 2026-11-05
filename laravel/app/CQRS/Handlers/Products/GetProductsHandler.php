<?php

namespace App\CQRS\Handlers\Products;

use App\CQRS\Queries\Products\GetProductsQuery;
use Illuminate\Database\Eloquent\Collection;

class GetProductsHandler extends AbstractProductHandler
{
    public function handle(GetProductsQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
