<?php

namespace App\CQRS\Commands\Shops;

use App\DTOs\DtoInterface;

class UpdateShopCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
