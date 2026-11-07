<?php

namespace App\CQRS\Handlers\PaperTypes;

use App\CQRS\Commands\PaperTypes\UpdatePaperTypeCommand;
use App\Exceptions\ApiException;
use App\Models\PaperType;

class UpdatePaperTypeHandler extends AbstractPaperTypeHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdatePaperTypeCommand $command): PaperType
    {
        return $this->repository->update($command->id, $command->dto);
    }
}
