<?php

namespace App\Http\Resources\Brands;

use App\Http\Resources\AbstractResourceCollection;

class BrandsResourceCollection extends AbstractResourceCollection
{
    public $collects = BrandResource::class;
}
