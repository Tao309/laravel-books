<?php

namespace App\Http\Resources\SourceProducts;

use App\Http\Resources\AbstractResourceCollection;

class SourceProductsResourceCollection extends AbstractResourceCollection
{
    public $collects = SourceProductResource::class;
}
