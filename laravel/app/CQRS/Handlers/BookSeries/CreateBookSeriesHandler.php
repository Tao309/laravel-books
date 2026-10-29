<?php

namespace App\CQRS\Handlers\BookSeries;

use App\CQRS\Commands\BookSeries\CreateBookSeriesCommand;
use App\Exceptions\ApiException;
use App\Models\BookSeries;

class CreateBookSeriesHandler extends AbstractBookSeriesHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreateBookSeriesCommand $command): BookSeries
    {
        return $this->repository->create($command->dto->toArray());
    }
}
