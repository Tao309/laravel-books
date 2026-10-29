<?php

namespace App\CQRS\Handlers\BookSeries;

use App\CQRS\Queries\BookSeries\GetBookSeriesByIdQuery;
use App\Models\BookSeries;

class GetBookSeriesByIdHandler extends AbstractBookSeriesHandler
{
    public function handle(GetBookSeriesByIdQuery $query): BookSeries
    {
        return $this->repository->get($query->id);
    }
}
