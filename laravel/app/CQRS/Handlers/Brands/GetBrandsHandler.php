<?php

namespace App\CQRS\Handlers\Brands;

use App\CQRS\Queries\Brands\GetBrandsQuery;
use Illuminate\Database\Eloquent\Collection;

class GetBrandsHandler extends AbstractBrandHandler
{
    public function handle(GetBrandsQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
