<?php

namespace App\CQRS\Handlers\Authors;

use App\CQRS\Queries\Authors\GetAuthorByIdQuery;
use App\Models\Author;

class GetAuthorByIdHandler extends AbstractAuthorHandler
{
    public function handle(GetAuthorByIdQuery $query): Author
    {
        return $this->repository->get($query->id);
    }
}
