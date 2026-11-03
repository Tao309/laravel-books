<?php

namespace App\CQRS\Commands\PaperTypes;

use App\DTOs\DtoInterface;

class CreatePaperTypeCommand
{
    public function __construct(public DtoInterface $dto) {}
}
