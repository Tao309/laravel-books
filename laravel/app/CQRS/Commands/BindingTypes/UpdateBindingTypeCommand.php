<?php

namespace App\CQRS\Commands\BindingTypes;

class UpdateBindingTypeCommand
{
    public function __construct(public int $id, public string $label) {}
}
