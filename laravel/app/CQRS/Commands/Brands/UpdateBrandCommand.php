<?php

namespace App\CQRS\Commands\Brands;

use App\DTOs\DtoInterface;

class UpdateBrandCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
