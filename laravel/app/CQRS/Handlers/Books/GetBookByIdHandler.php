<?php

namespace App\CQRS\Handlers\Books;

use App\CQRS\Queries\Books\GetBookByIdQuery;
use App\Models\Book;

class GetBookByIdHandler extends AbstractBookHandler
{
    public function handle(GetBookByIdQuery $query): Book
    {
        return $this->repository->get($query->id);
    }
}
