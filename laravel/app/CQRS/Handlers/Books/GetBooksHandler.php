<?php

namespace App\CQRS\Handlers\Books;

use App\CQRS\Queries\Books\GetBooksQuery;
use Illuminate\Database\Eloquent\Collection;

class GetBooksHandler extends AbstractBookHandler
{
    public function handle(GetBooksQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
