<?php

namespace App\CQRS\Handlers\SourceProductTypes;

use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypesPaginateQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class GetSourceProductTypesPaginateHandler extends AbstractSourceProductTypeHandler
{
    public function handle(GetSourceProductTypesPaginateQuery $query): LengthAwarePaginator
    {
        return $this->repository->getAllPaginate($query);
    }
}
