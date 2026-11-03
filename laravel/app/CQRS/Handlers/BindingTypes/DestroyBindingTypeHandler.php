<?php

namespace App\CQRS\Handlers\BindingTypes;

use App\CQRS\Commands\BindingTypes\DestroyBindingTypeCommand;
use App\Exceptions\ApiException;

class DestroyBindingTypeHandler extends AbstractBindingTypeHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroyBindingTypeCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
