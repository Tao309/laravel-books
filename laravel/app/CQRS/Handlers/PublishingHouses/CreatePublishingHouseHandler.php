<?php

namespace App\CQRS\Handlers\PublishingHouses;

use App\CQRS\Commands\PublishingHouses\CreatePublishingHouseCommand;
use App\Exceptions\ApiException;
use App\Models\PublishingHouse;

class CreatePublishingHouseHandler extends AbstractPublishingHouseHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreatePublishingHouseCommand $command): PublishingHouse
    {
        return $this->repository->create($command->dto);
    }
}
