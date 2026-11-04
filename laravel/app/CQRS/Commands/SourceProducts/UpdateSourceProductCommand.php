<?php

namespace App\CQRS\Commands\SourceProducts;

use App\DTOs\DtoInterface;

class UpdateSourceProductCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
