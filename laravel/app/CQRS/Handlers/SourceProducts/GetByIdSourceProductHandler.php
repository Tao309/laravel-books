<?php

namespace App\CQRS\Handlers\SourceProducts;

use App\CQRS\Queries\SourceProducts\GetByIdSourceProductQuery;
use App\Models\SourceProduct;

class GetByIdSourceProductHandler extends AbstractSourceProductHandler
{
    public function handle(GetByIdSourceProductQuery $query): SourceProduct
    {
        return $this->repository->get($query->id);
    }
}
