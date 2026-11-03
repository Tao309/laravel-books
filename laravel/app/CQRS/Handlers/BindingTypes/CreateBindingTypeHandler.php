<?php

namespace App\CQRS\Handlers\BindingTypes;

use App\CQRS\Commands\BindingTypes\CreateBindingTypeCommand;
use App\Exceptions\ApiException;
use App\Models\BindingType;

class CreateBindingTypeHandler extends AbstractBindingTypeHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreateBindingTypeCommand $command): BindingType
    {
        return $this->repository->create($command->dto->toArray());
    }
}
