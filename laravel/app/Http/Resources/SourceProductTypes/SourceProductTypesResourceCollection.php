<?php

namespace App\Http\Resources\SourceProductTypes;

use App\Http\Resources\AbstractResourceCollection;

class SourceProductTypesResourceCollection extends AbstractResourceCollection
{
    public $collects = SourceProductTypeResource::class;
}
