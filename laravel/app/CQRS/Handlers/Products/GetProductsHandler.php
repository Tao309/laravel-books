<?php

namespace App\CQRS\Handlers\Products;

use App\CQRS\Queries\Products\GetProductsQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class GetProductsHandler extends AbstractProductHandler
{
    public function handle(GetProductsQuery $query): LengthAwarePaginator
    {
        return $this->repository->getAllPaginate($query);
    }
}
