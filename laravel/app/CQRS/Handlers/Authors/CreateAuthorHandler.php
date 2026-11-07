<?php

namespace App\CQRS\Handlers\Authors;

use App\CQRS\Commands\Authors\CreateAuthorCommand;
use App\Exceptions\ApiException;
use App\Models\Author;

class CreateAuthorHandler extends AbstractAuthorHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreateAuthorCommand $command): Author
    {
        return $this->repository->create($command->dto);
    }
}
