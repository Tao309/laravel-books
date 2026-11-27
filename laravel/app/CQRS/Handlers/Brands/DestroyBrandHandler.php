<?php

namespace App\CQRS\Handlers\Brands;

use App\CQRS\Commands\Brands\DestroyBrandCommand;
use App\Exceptions\ApiException;

class DestroyBrandHandler extends AbstractBrandHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroyBrandCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
