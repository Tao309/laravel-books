<?php

namespace App\CQRS\Handlers\PublishingBrands;

use App\CQRS\Commands\PublishingBrands\CreatePublishingBrandCommand;
use App\Exceptions\ApiException;
use App\Models\PublishingBrand;

class CreatePublishingBrandHandler extends AbstractPublishingBrandHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreatePublishingBrandCommand $command): PublishingBrand
    {
        return $this->repository->create($command->dto->toArray());
    }
}
