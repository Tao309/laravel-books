<?php

namespace App\Queries\Authors;

use App\Queries\GetByIdQueryInterface;

class GetAuthorByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
