<?php

namespace App\Commands\Authors;

class CreateAuthorCommand
{
    public function __construct(public string $firstName, public ?string $lastName) {}
}
