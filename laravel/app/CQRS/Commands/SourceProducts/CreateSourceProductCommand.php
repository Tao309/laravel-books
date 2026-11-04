<?php

namespace App\CQRS\Commands\SourceProducts;

use App\DTOs\DtoInterface;

class CreateSourceProductCommand
{
    public function __construct(public DtoInterface $dto) {}
}
