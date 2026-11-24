<?php

namespace App\CQRS\Handlers\BookSeries;

use App\CQRS\Commands\BookSeries\DestroyBookSeriesCommand;
use App\CQRS\Handlers\Books\AbstractBookHandler;
use App\Exceptions\ApiException;

class DestroyBookSeriesHandler extends AbstractBookHandler
{
    /**
     * @throws ApiException
     */
    public function handle(DestroyBookSeriesCommand $command): bool
    {
        return $this->repository->delete($command->id);
    }
}
