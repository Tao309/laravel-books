<?php

namespace App\CQRS\Handlers\Products;

use App\CQRS\Commands\Products\UpdateProductCommand;
use App\Exceptions\ApiException;
use App\Models\Product;

class UpdateProductHandler extends AbstractProductHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdateProductCommand $command): Product
    {
        return $this->repository->update($command->id, $command->dto);
    }
}
