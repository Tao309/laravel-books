<?php

namespace App\CQRS\Handlers\Authors;

use App\CQRS\Queries\Authors\GetAuthorsQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAuthorsHandler extends AbstractAuthorHandler
{
    public function handle(GetAuthorsQuery $query): LengthAwarePaginator
    {
        return $this->repository->getAllPaginate($query);
    }
}
