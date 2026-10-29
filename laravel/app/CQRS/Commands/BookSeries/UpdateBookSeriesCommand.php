<?php

namespace App\CQRS\Commands\BookSeries;

use App\DTOs\DtoInterface;

class UpdateBookSeriesCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
