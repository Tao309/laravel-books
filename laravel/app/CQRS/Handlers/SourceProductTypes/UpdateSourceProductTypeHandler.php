<?php

namespace App\CQRS\Handlers\SourceProductTypes;

use App\CQRS\Commands\SourceProductTypes\UpdateSourceProductTypeCommand;
use App\Exceptions\ApiException;
use App\Models\SourceProductType;

class UpdateSourceProductTypeHandler extends AbstractSourceProductTypeHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdateSourceProductTypeCommand $command): SourceProductType
    {
        return $this->repository->update($command->id, $command->dto->toArray());
    }
}
