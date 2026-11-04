<?php

namespace App\CQRS\Handlers\SourceProductTypes;

use App\CQRS\Commands\SourceProductTypes\DestroySourceProductTypeCommand;
use App\Exceptions\ApiException;

class DestroySourceProductTypeHandler extends AbstractSourceProductTypeHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroySourceProductTypeCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
