<?php

namespace App\CQRS\Handlers\Books;

use App\CQRS\Queries\Books\GetBooksPaginateQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class GetBooksPaginateHandler extends AbstractBookHandler
{
    public function handle(GetBooksPaginateQuery $query): LengthAwarePaginator
    {
        return $this->repository->getAllPaginate($query);
    }
}
