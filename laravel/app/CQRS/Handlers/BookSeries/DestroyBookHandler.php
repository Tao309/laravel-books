<?php

namespace App\CQRS\Handlers\BookSeries;

use App\CQRS\Commands\Books\DestroyBookCommand;
use App\CQRS\Handlers\Books\AbstractBookHandler;
use App\Exceptions\ApiException;

class DestroyBookHandler extends AbstractBookHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroyBookCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
