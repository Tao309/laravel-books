<?php

namespace App\CQRS\Queries\PaperTypes;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetByIdPaperTypeQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
