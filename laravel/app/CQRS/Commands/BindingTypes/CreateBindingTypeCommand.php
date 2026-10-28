<?php

namespace App\CQRS\Commands\BindingTypes;

class CreateBindingTypeCommand
{
    public function __construct(public string $label) {}
}
