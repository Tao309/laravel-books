<?php

namespace App\CQRS\Queries\Books;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetByIdBookQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
