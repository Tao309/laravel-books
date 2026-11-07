<?php

namespace App\CQRS\Handlers\PublishingHouses;

use App\CQRS\Commands\PublishingHouses\UpdatePublishingHouseCommand;
use App\Exceptions\ApiException;
use App\Models\PublishingHouse;

class UpdatePublishingHouseHandler extends AbstractPublishingHouseHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdatePublishingHouseCommand $command): PublishingHouse
    {
        return $this->repository->update($command->id, $command->dto);
    }
}
