<?php

namespace App\CQRS\Queries\Books;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetBookByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
