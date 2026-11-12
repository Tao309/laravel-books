<?php

namespace App\Http\Resources\BindingTypes;

use App\Http\Resources\AbstractResourceCollection;

class BindingTypesResourceCollection extends AbstractResourceCollection
{
    public $collects = BindingTypeResource::class;
}
