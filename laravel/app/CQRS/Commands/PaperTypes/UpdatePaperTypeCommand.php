<?php

namespace App\CQRS\Commands\PaperTypes;

use App\DTOs\DtoInterface;

class UpdatePaperTypeCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
