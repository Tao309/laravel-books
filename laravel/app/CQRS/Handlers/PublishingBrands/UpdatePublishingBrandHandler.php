<?php

namespace App\CQRS\Handlers\PublishingBrands;

use App\CQRS\Commands\PublishingBrands\UpdatePublishingBrandCommand;
use App\Exceptions\ApiException;
use App\Models\PublishingBrand;

class UpdatePublishingBrandHandler extends AbstractPublishingBrandHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdatePublishingBrandCommand $command): PublishingBrand
    {
        return $this->repository->update($command->id, $command->dto->toArray());
    }
}
