<?php

namespace App\CQRS\Commands\Authors;

use App\DTOs\DtoInterface;

class CreateAuthorCommand
{
    public function __construct(public DtoInterface $dto) {}
}
