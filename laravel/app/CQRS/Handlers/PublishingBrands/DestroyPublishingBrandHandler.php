<?php

namespace App\CQRS\Handlers\PublishingBrands;

use App\CQRS\Commands\PublishingBrands\DestroyPublishingBrandCommand;
use App\Exceptions\ApiException;

class DestroyPublishingBrandHandler extends AbstractPublishingBrandHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroyPublishingBrandCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
