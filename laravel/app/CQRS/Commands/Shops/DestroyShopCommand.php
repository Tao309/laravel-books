<?php

namespace App\CQRS\Commands\Shops;

class DestroyShopCommand
{
    public function __construct(public int $id) {}
}
