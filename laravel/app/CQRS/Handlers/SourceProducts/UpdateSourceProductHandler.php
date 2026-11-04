<?php

namespace App\CQRS\Handlers\SourceProducts;

use App\CQRS\Commands\SourceProducts\UpdateSourceProductCommand;
use App\Exceptions\ApiException;
use App\Models\SourceProduct;

class UpdateSourceProductHandler extends AbstractSourceProductHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdateSourceProductCommand $command): SourceProduct
    {
        return $this->repository->update($command->id, $command->dto->toArray());
    }
}
