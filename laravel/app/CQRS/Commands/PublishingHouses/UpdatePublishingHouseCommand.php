<?php

namespace App\CQRS\Commands\PublishingHouses;

use App\DTOs\PublishingHouseDTO;

class UpdatePublishingHouseCommand
{
    public function __construct(public int $id, public PublishingHouseDTO $dto) {}
}
