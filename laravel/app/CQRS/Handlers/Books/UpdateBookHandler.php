<?php

namespace App\CQRS\Handlers\Books;

use App\CQRS\Commands\Books\UpdateBookCommand;
use App\Exceptions\ApiException;
use App\Models\Book;

class UpdateBookHandler extends AbstractBookHandler
{
    /**
     * @throws ApiException
     */
    public function handle(UpdateBookCommand $command): Book
    {
        return $this->repository->update($command->id, $command->dto);
    }
}
