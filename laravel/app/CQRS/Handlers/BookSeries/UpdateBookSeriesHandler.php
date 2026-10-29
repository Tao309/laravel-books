<?php

namespace App\CQRS\Handlers\BookSeries;

use App\CQRS\Commands\BookSeries\UpdateBookSeriesCommand;
use App\Exceptions\ApiException;
use App\Models\BookSeries;

class UpdateBookSeriesHandler extends AbstractBookSeriesHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdateBookSeriesCommand $command): BookSeries
    {
        return $this->repository->update($command->id, $command->dto->toArray());
    }
}
