<?php

namespace App\CQRS\Commands\PublishingHouses;

use App\DTOs\DtoInterface;

class UpdatePublishingHouseCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
