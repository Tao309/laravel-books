<?php

namespace App\CQRS\Commands\SourceProducts;

class DestroySourceProductCommand
{
    public function __construct(public int $id) {}
}
