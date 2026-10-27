<?php

namespace App\Commands\Authors;

class DestroyAuthorCommand
{
    public function __construct(public int $id) {}
}
