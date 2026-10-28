<?php

namespace App\CQRS\Queries\PublishingHouses;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetPublishingHouseByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
