<?php

namespace App\CQRS\Commands\Brands;

class DestroyBrandCommand
{
    public function __construct(public int $id) {}
}
