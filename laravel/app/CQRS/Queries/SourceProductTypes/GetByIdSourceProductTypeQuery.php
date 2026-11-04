<?php

namespace App\CQRS\Queries\SourceProductTypes;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetByIdSourceProductTypeQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
