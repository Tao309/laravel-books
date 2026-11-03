<?php

namespace App\CQRS\Handlers\BindingTypes;

use App\CQRS\Commands\BindingTypes\UpdateBindingTypeCommand;
use App\Exceptions\ApiException;
use App\Models\BindingType;

class UpdateBindingTypeHandler extends AbstractBindingTypeHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdateBindingTypeCommand $command): BindingType
    {
        return $this->repository->update($command->id, $command->dto->toArray());
    }
}
