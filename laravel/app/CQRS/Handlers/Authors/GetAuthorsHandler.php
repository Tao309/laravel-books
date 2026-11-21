<?php

namespace App\CQRS\Handlers\Authors;

use App\CQRS\Queries\Authors\GetAuthorsQuery;
use Illuminate\Database\Eloquent\Collection;

class GetAuthorsHandler extends AbstractAuthorHandler
{
    public function handle(GetAuthorsQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
