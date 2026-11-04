<?php

namespace App\CQRS\Handlers\SourceProducts;

use App\CQRS\Queries\SourceProducts\GetSourceProductsQuery;
use Illuminate\Database\Eloquent\Collection;

class GetSourceProductsHandler extends AbstractSourceProductHandler
{
    public function handle(GetSourceProductsQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
