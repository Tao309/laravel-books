<?php

namespace App\CQRS\Commands\Brands;

use App\DTOs\DtoInterface;

class CreateBrandCommand
{
    public function __construct(public DtoInterface $dto) {}
}
