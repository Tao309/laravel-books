<?php

namespace App\CQRS\Commands\Cities;

class DestroyCityCommand
{
    public function __construct(public int $id) {}
}
