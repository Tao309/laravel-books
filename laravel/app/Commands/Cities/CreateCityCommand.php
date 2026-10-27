<?php

namespace App\Commands\Cities;

class CreateCityCommand
{
    public function __construct(public string $code, public string $title) {}
}
