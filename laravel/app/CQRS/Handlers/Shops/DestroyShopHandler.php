<?php

namespace App\CQRS\Handlers\Shops;

use App\CQRS\Commands\Shops\DestroyShopCommand;
use App\Exceptions\ApiException;

class DestroyShopHandler extends AbstractShopHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroyShopCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
