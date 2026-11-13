<?php

namespace App\CQRS\Commands\Shops;

use App\DTOs\DtoInterface;

class CreateShopCommand
{
    public function __construct(public DtoInterface $dto) {}
}
