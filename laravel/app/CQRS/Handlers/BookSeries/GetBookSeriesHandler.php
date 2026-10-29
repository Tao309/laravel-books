<?php

namespace App\CQRS\Handlers\BookSeries;

use App\CQRS\Queries\BookSeries\GetBookSeriesQuery;
use Illuminate\Database\Eloquent\Collection;

class GetBookSeriesHandler extends AbstractBookSeriesHandler
{
    public function handle(GetBookSeriesQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
