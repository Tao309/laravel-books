<?php

namespace App\CQRS\Handlers\SourceProductTypes;

use App\CQRS\Commands\SourceProductTypes\CreateSourceProductTypeCommand;
use App\Exceptions\ApiException;
use App\Models\SourceProductType;

class CreateSourceProductTypeHandler extends AbstractSourceProductTypeHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreateSourceProductTypeCommand $command): SourceProductType
    {
        return $this->repository->create($command->dto->toArray());
    }
}
