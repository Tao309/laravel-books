<?php

namespace App\CQRS\Commands\Authors;

class CreateAuthorCommand
{
    public function __construct(public string $firstName, public ?string $lastName) {}
}
