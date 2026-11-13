<?php

namespace App\CQRS\Handlers\SourceProducts;

use App\CQRS\Queries\SourceProducts\GetSourceProductByIdSQuery;
use App\Models\SourceProduct;

class GetSourceProductByIdSHandler extends AbstractSourceProductHandler
{
    public function handle(GetSourceProductByIdSQuery $query): SourceProduct
    {
        return $this->repository->get($query->id);
    }
}
