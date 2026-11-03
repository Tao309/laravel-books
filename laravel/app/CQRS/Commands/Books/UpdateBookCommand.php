<?php

namespace App\CQRS\Commands\Books;

use App\DTOs\DtoInterface;

class UpdateBookCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
