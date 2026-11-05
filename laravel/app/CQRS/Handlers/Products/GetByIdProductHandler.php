<?php

namespace App\CQRS\Handlers\Products;

use App\CQRS\Queries\Products\GetByIdProductQuery;
use App\Models\Product;

class GetByIdProductHandler extends AbstractProductHandler
{
    public function handle(GetByIdProductQuery $query): Product
    {
        return $this->repository->get($query->id);
    }
}
