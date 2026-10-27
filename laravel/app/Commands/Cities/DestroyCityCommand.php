<?php

namespace App\Commands\Cities;

class DestroyCityCommand
{
    public function __construct(public int $id) {}
}
