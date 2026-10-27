<?php

namespace App\Commands\BindingTypes;

class CreateBindingTypeCommand
{
    public function __construct(public string $label) {}
}
