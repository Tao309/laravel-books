<?php

namespace App\CQRS\Handlers\Authors;

use App\CQRS\Queries\Authors\GetAuthorsPaginateQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAuthorsPaginateHandler extends AbstractAuthorHandler
{
    public function handle(GetAuthorsPaginateQuery $query): LengthAwarePaginator
    {
        return $this->repository->getAllPaginate($query);
    }
}
