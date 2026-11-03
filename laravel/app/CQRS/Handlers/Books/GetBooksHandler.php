<?php

namespace App\CQRS\Handlers\Books;

use App\CQRS\Queries\Books\GetBooksQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class GetBooksHandler extends AbstractBookHandler
{
    public function handle(GetBooksQuery $query): LengthAwarePaginator
    {
        return $this->repository->getAllPaginate($query);
    }
}
