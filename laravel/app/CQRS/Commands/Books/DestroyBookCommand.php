<?php

namespace App\CQRS\Commands\Books;

class DestroyBookCommand
{
    public function __construct(public int $id) {}
}
