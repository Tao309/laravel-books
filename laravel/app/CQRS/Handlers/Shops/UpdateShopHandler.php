<?php

namespace App\CQRS\Handlers\Shops;

use App\CQRS\Commands\Shops\UpdateShopCommand;
use App\Exceptions\ApiException;
use App\Models\Shop;

class UpdateShopHandler extends AbstractShopHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdateShopCommand $command): Shop
    {
        return $this->repository->update($command->id, $command->dto);
    }
}
