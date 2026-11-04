<?php

namespace App\CQRS\Handlers\SourceProducts;

use App\CQRS\Commands\SourceProducts\DestroySourceProductCommand;
use App\Exceptions\ApiException;

class DestroySourceProductHandler extends AbstractSourceProductHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroySourceProductCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
