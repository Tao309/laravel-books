<?php

namespace App\CQRS\Commands\BindingTypes;

use App\DTOs\DtoInterface;

class CreateBindingTypeCommand
{
    public function __construct(public DtoInterface $dto) {}
}
