<?php

namespace App\Queries\Shops;

use App\Queries\GetByIdQueryInterface;

class GetShopByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
