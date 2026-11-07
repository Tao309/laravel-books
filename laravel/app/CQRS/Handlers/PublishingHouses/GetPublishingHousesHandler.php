<?php

namespace App\CQRS\Handlers\PublishingHouses;

use App\CQRS\Queries\PublishingHouses\GetPublishingHousesQuery;
use Illuminate\Database\Eloquent\Collection;

class GetPublishingHousesHandler extends AbstractPublishingHouseHandler
{
    public function handle(GetPublishingHousesQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
