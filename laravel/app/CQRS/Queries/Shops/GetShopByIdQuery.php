<?php

namespace App\CQRS\Queries\Shops;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetShopByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
