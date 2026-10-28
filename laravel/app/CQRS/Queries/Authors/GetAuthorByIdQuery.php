<?php

namespace App\CQRS\Queries\Authors;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetAuthorByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
