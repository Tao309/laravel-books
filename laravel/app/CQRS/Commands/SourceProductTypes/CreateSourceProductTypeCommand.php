<?php

namespace App\CQRS\Commands\SourceProductTypes;

use App\DTOs\DtoInterface;

class CreateSourceProductTypeCommand
{
    public function __construct(public DtoInterface $dto) {}
}
