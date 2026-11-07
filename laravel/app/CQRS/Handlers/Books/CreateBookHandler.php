<?php

namespace App\CQRS\Handlers\Books;

use App\CQRS\Commands\Books\CreateBookCommand;
use App\Exceptions\ApiException;
use App\Models\Book;

class CreateBookHandler extends AbstractBookHandler
{
    /**
     * @throws ApiException
     */
    public function handle(CreateBookCommand $command): Book
    {
        return $this->repository->create($command->dto);
    }
}
