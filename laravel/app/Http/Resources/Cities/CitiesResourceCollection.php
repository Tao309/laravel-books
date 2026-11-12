<?php

namespace App\Http\Resources\Cities;

use App\Http\Resources\AbstractResourceCollection;

class CitiesResourceCollection extends AbstractResourceCollection
{
    public $collects = CityResource::class;
}
