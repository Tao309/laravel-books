<?php

namespace App\CQRS\Handlers\SourceProducts;

use App\CQRS\Commands\SourceProducts\CreateSourceProductCommand;
use App\Exceptions\ApiException;
use App\Models\SourceProduct;

class CreateSourceProductHandler extends AbstractSourceProductHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreateSourceProductCommand $command): SourceProduct
    {
        return $this->repository->create($command->dto);
    }
}
