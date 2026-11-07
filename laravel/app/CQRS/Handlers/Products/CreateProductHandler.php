<?php

namespace App\CQRS\Handlers\Products;

use App\CQRS\Commands\Products\CreateProductCommand;
use App\Exceptions\ApiException;
use App\Models\Product;

class CreateProductHandler extends AbstractProductHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreateProductCommand $command): Product
    {
        return $this->repository->create($command->dto);
    }
}
