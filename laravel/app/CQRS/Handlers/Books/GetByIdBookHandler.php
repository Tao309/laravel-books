<?php

namespace App\CQRS\Handlers\Books;

use App\CQRS\Queries\Books\GetByIdBookQuery;
use App\Models\Book;

class GetByIdBookHandler extends AbstractBookHandler
{
    public function handle(GetByIdBookQuery $query): Book
    {
        return $this->repository->get($query->id);
    }
}
