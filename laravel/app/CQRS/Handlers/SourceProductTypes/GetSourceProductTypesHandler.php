<?php

namespace App\CQRS\Handlers\SourceProductTypes;

use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypesQuery;
use Illuminate\Database\Eloquent\Collection;

class GetSourceProductTypesHandler extends AbstractSourceProductTypeHandler
{
    public function handle(GetSourceProductTypesQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
