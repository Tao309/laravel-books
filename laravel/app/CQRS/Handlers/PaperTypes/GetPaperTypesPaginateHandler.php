<?php

namespace App\CQRS\Handlers\PaperTypes;

use App\CQRS\Queries\PaperTypes\GetPaperTypesPaginateQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class GetPaperTypesPaginateHandler extends AbstractPaperTypeHandler
{
    public function handle(GetPaperTypesPaginateQuery $query): LengthAwarePaginator
    {
        return $this->repository->getAllPaginate($query);
    }
}
