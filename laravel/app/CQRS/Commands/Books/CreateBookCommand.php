<?php

namespace App\CQRS\Commands\Books;

use App\DTOs\DtoInterface;

class CreateBookCommand
{
    public function __construct(public DtoInterface $dto) {}
}
