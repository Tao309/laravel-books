<?php

namespace App\CQRS\Handlers\Cities;

use App\CQRS\Commands\Cities\CreateCityCommand;
use App\Exceptions\ApiException;
use App\Models\City;

class CreateCityHandler extends AbstractCityHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreateCityCommand $command): City
    {
        return $this->repository->create($command->dto);
    }
}
