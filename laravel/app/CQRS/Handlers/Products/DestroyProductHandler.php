<?php

namespace App\CQRS\Handlers\Products;

use App\CQRS\Commands\Products\DestroyProductCommand;
use App\Exceptions\ApiException;

class DestroyProductHandler extends AbstractProductHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroyProductCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
