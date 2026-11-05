<?php

namespace App\CQRS\Commands\Products;

class DestroyProductCommand
{
    public function __construct(public int $id) {}
}
