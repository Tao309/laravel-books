<?php

namespace App\CQRS\Commands\BookSeries;

use App\DTOs\DtoInterface;

class CreateBookSeriesCommand
{
    public function __construct(public DtoInterface $dto) {}
}
