<?php

namespace App\CQRS\Commands\BindingTypes;

class DestroyBindingTypeCommand
{
    public function __construct(public int $id) {}
}
