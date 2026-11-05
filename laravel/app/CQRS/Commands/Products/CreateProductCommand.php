<?php

namespace App\CQRS\Commands\Products;

use App\DTOs\DtoInterface;

class CreateProductCommand
{
    public function __construct(public DtoInterface $dto) {}
}
