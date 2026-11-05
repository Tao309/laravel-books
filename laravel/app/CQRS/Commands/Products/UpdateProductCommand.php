<?php

namespace App\CQRS\Commands\Products;

use App\DTOs\DtoInterface;

class UpdateProductCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
