<?php

namespace App\CQRS\Queries\SourceProducts;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetSourceProductByIdSQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
