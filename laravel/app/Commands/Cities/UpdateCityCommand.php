<?php

namespace App\Commands\Cities;

class UpdateCityCommand
{
    public function __construct(public int $id, public ?string $code, public string $title) {}
}
