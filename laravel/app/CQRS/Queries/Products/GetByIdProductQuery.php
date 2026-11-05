<?php

namespace App\CQRS\Queries\Products;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetByIdProductQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
