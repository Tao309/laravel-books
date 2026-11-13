<?php

namespace App\CQRS\Handlers\Shops;

use App\CQRS\Commands\Shops\CreateShopCommand;
use App\Exceptions\ApiException;
use App\Models\Shop;

class CreateShopHandler extends AbstractShopHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreateShopCommand $command): Shop
    {
        return $this->repository->create($command->dto);
    }
}
