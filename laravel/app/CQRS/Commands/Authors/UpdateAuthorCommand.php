<?php

namespace App\CQRS\Commands\Authors;

class UpdateAuthorCommand
{
    public function __construct(public int $id, public ?string $firstName, public ?string $lastName) {}
}
