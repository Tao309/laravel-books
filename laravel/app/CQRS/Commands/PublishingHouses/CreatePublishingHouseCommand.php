<?php

namespace App\CQRS\Commands\PublishingHouses;

use App\DTOs\DtoInterface;

class CreatePublishingHouseCommand
{
    public function __construct(public DtoInterface $dto) {}
}
