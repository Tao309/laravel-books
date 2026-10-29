<?php

namespace App\CQRS\Handlers\PublishingBrands;

use App\CQRS\Queries\PublishingBrands\GetPublishingBrandsQuery;
use Illuminate\Database\Eloquent\Collection;

class GetPublishingBrandsHandler extends AbstractPublishingBrandHandler
{
    public function handle(GetPublishingBrandsQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
