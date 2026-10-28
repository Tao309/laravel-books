<?php

namespace App\CQRS\Commands\PublishingHouses;

use App\DTOs\PublishingHouseDTO;

class CreatePublishingHouseCommand
{
    public function __construct(public PublishingHouseDTO $dto) {}
}
