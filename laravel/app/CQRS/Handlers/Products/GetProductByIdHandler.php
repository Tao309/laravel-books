<?php

namespace App\CQRS\Handlers\Products;

use App\CQRS\Queries\Products\GetProductByIdQuery;
use App\Models\Product;

class GetProductByIdHandler extends AbstractProductHandler
{
    public function handle(GetProductByIdQuery $query): Product
    {
        return $this->repository->get($query->id);
    }
}
