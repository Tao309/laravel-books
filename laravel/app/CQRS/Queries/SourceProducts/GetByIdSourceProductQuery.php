<?php

namespace App\CQRS\Queries\SourceProducts;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetByIdSourceProductQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
