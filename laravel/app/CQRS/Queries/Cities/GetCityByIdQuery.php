<?php

namespace App\CQRS\Queries\Cities;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetCityByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
