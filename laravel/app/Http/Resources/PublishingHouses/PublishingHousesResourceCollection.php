<?php

namespace App\Http\Resources\PublishingHouses;

use App\Http\Resources\AbstractResourceCollection;

class PublishingHousesResourceCollection extends AbstractResourceCollection
{
    public $collects = PublishingHouseResource::class;
}
