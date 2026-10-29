<?php

namespace App\CQRS\Queries\BookSeries;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetBookSeriesByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
