<?php

namespace App\CQRS\Handlers\PublishingBrands;

use App\CQRS\Queries\PublishingBrands\GetPublishingBrandByIdQuery;
use App\Models\PublishingBrand;

class GetPublishingBrandByIdHandler extends AbstractPublishingBrandHandler
{
    public function handle(GetPublishingBrandByIdQuery $query): PublishingBrand
    {
        return $this->repository->get($query->id);
    }
}
