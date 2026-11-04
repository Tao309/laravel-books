<?php

namespace App\CQRS\Commands\SourceProductTypes;

class DestroySourceProductTypeCommand
{
    public function __construct(public int $id) {}
}
