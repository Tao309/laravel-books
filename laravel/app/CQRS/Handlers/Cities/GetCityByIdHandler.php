<?php

namespace App\CQRS\Handlers\Cities;

use App\CQRS\Handlers\PaperTypes\AbstractPaperTypeHandler;
use App\CQRS\Queries\Cities\GetCityByIdQuery;
use App\Models\PaperType;

class GetCityByIdHandler extends AbstractPaperTypeHandler
{
    public function handle(GetCityByIdQuery $query): PaperType
    {
        return $this->repository->get($query->id);
    }
}
