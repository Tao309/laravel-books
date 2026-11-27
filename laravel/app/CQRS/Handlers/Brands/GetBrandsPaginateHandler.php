<?php

namespace App\CQRS\Handlers\Brands;

use App\CQRS\Queries\Brands\GetBrandsPaginateQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class GetBrandsPaginateHandler extends AbstractBrandHandler
{
    public function handle(GetBrandsPaginateQuery $query): LengthAwarePaginator
    {
        return $this->repository->getAllPaginate($query);
    }
}
