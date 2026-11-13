<?php

namespace App\CQRS\Handlers\PublishingHouses;

use App\CQRS\Commands\PublishingHouses\DestroyPublishingHouseCommand;
use App\Exceptions\ApiException;

class DestroyPublishingHouseHandler extends AbstractPublishingHouseHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroyPublishingHouseCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
