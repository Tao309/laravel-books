<?php

namespace App\CQRS\Handlers\SourceProducts;

use App\CQRS\Queries\SourceProducts\GetSourceProductsPaginateQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class GetSourceProductsPaginateHandler extends AbstractSourceProductHandler
{
    public function handle(GetSourceProductsPaginateQuery $query): LengthAwarePaginator
    {
        return $this->repository->getAllPaginate($query);
    }
}
