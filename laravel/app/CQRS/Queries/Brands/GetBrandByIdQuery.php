<?php

namespace App\CQRS\Queries\Brands;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetBrandByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
