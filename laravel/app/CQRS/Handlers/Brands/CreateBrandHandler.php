<?php

namespace App\CQRS\Handlers\Brands;

use App\CQRS\Commands\Brands\CreateBrandCommand;
use App\Exceptions\ApiException;
use App\Models\Brand;

class CreateBrandHandler extends AbstractBrandHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreateBrandCommand $command): Brand
    {
        return $this->repository->create($command->dto);
    }
}
