<?php

namespace App\CQRS\Handlers\Cities;

use App\CQRS\Queries\Cities\GetCitiesQuery;
use Illuminate\Database\Eloquent\Collection;

class GetCitiesHandler extends AbstractCityHandler
{
    public function handle(GetCitiesQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
