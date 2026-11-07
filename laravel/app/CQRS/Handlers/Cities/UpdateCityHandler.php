<?php

namespace App\CQRS\Handlers\Cities;

use App\CQRS\Commands\Cities\UpdateCityCommand;
use App\Exceptions\ApiException;
use App\Models\City;

class UpdateCityHandler extends AbstractCityHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdateCityCommand $command): City
    {
        return $this->repository->update($command->id, $command->dto);
    }
}
