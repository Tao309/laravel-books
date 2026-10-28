<?php

namespace App\CQRS\Commands\Cities;

use App\DTOs\DtoInterface;

class CreateCityCommand
{
    public function __construct(public DtoInterface $dto) {}
}
