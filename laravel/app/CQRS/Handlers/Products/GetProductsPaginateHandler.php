<?php

namespace App\CQRS\Handlers\Products;

use App\CQRS\Queries\Products\GetProductsPaginateQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class GetProductsPaginateHandler extends AbstractProductHandler
{
    public function handle(GetProductsPaginateQuery $query): LengthAwarePaginator
    {
        return $this->repository->getAllPaginate($query);
    }
}
