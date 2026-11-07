<?php

namespace App\CQRS\Handlers\Authors;

use App\CQRS\Commands\Authors\DestroyAuthorCommand;
use App\Exceptions\ApiException;

class DestroyAuthorHandler extends AbstractAuthorHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroyAuthorCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
