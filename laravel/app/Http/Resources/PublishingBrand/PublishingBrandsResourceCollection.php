<?php

namespace App\Http\Resources\PublishingBrand;

use App\Http\Resources\AbstractResourceCollection;

class PublishingBrandsResourceCollection extends AbstractResourceCollection
{
    public $collects = PublishingBrandResource::class;
}
