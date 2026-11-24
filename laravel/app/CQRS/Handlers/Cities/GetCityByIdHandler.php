<?php

namespace App\CQRS\Handlers\Cities;

use App\CQRS\Queries\Cities\GetCityByIdQuery;
use App\Models\City;

class GetCityByIdHandler extends AbstractCityHandler
{
    public function handle(GetCityByIdQuery $query): City
    {
        return $this->repository->get($query->id);
    }
}
