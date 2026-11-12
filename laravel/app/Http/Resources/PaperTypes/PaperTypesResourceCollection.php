<?php

namespace App\Http\Resources\PaperTypes;

use App\Http\Resources\AbstractResourceCollection;

class PaperTypesResourceCollection extends AbstractResourceCollection
{
    public $collects = PaperTypeResource::class;
}
