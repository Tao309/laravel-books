<?php

namespace App\CQRS\Handlers\Brands;

use App\CQRS\Commands\Brands\UpdateBrandCommand;
use App\Exceptions\ApiException;
use App\Models\Brand;

class UpdateBrandHandler extends AbstractBrandHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdateBrandCommand $command): Brand
    {
        return $this->repository->update($command->id, $command->dto);
    }
}
