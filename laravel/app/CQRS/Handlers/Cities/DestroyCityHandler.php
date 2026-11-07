<?php

namespace App\CQRS\Handlers\Cities;

use App\CQRS\Commands\Cities\DestroyCityCommand;
use App\Exceptions\ApiException;

class DestroyCityHandler extends AbstractCityHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroyCityCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
