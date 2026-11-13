<?php

namespace App\Http\Resources\Shops;

use App\Http\Resources\AbstractResourceCollection;

class ShopsResourceCollection extends AbstractResourceCollection
{
    public $collects = ShopResource::class;
}
