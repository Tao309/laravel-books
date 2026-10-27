<?php

namespace App\Queries\Authors;

use App\Queries\GetByIdQueryInterface;

class GetByIdAuthorQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
