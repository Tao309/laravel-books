<?php

namespace App\CQRS\Handlers\PaperTypes;

use App\CQRS\Commands\PaperTypes\DestroyPaperTypeCommand;
use App\Exceptions\ApiException;

class DestroyPaperTypeHandler extends AbstractPaperTypeHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroyPaperTypeCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
