<?php

namespace App\CQRS\Handlers\PublishingHouses;

use App\CQRS\Queries\PublishingHouses\GetPublishingHouseByIdQuery;
use App\Models\PublishingHouse;

class GetPublishingHouseByIdHandler extends AbstractPublishingHouseHandler
{
    public function handle(GetPublishingHouseByIdQuery $query): PublishingHouse
    {
        return $this->repository->get($query->id);
    }
}
