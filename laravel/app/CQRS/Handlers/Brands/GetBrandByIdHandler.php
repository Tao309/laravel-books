<?php

namespace App\CQRS\Handlers\Brands;

use App\CQRS\Queries\Brands\GetBrandByIdQuery;
use App\Models\Brand;

class GetBrandByIdHandler extends AbstractBrandHandler
{
    public function handle(GetBrandByIdQuery $query): Brand
    {
        return $this->repository->get($query->id);
    }
}
