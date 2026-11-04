<?php

namespace App\CQRS\Commands\SourceProductTypes;

use App\DTOs\DtoInterface;

class UpdateSourceProductTypeCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
