<?php

namespace App\CQRS\Commands\PaperTypes;

class DestroyPaperTypeCommand
{
    public function __construct(public int $id) {}
}
