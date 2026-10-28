<?php

namespace App\CQRS\Commands\BindingTypes;

use App\DTOs\DtoInterface;

class UpdateBindingTypeCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
