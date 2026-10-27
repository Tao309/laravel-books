<?php

namespace App\Queries\Cities;

use App\Queries\GetByIdQueryInterface;

class GetCityByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
