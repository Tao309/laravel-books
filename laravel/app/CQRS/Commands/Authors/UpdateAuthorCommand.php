<?php

namespace App\CQRS\Commands\Authors;

use App\DTOs\DtoInterface;

class UpdateAuthorCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
