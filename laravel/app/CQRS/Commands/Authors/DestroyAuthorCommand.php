<?php

namespace App\CQRS\Commands\Authors;

class DestroyAuthorCommand
{
    public function __construct(public int $id) {}
}
