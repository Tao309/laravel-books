<?php

namespace App\CQRS\Handlers\Authors;

use App\CQRS\Commands\Authors\UpdateAuthorCommand;
use App\Exceptions\ApiException;
use App\Models\Author;

class UpdateAuthorHandler extends AbstractAuthorHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdateAuthorCommand $command): Author
    {
        return $this->repository->update($command->id, $command->dto);
    }
}
