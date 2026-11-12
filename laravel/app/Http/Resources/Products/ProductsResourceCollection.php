<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\AbstractResourceCollection;

class ProductsResourceCollection extends AbstractResourceCollection
{
    public $collects = ProductResource::class;
}
