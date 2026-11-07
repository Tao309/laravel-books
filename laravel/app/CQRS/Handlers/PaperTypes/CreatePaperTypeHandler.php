<?php

namespace App\CQRS\Handlers\PaperTypes;

use App\CQRS\Commands\PaperTypes\CreatePaperTypeCommand;
use App\Exceptions\ApiException;
use App\Models\PaperType;

class CreatePaperTypeHandler extends AbstractPaperTypeHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreatePaperTypeCommand $command): PaperType
    {
        return $this->repository->create($command->dto);
    }
}
