<?php

namespace App\CQRS\Commands\Cities;

use App\DTOs\DtoInterface;

class UpdateCityCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
