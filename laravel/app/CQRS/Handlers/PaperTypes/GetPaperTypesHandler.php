<?php

namespace App\CQRS\Handlers\PaperTypes;

use App\CQRS\Queries\PaperTypes\GetPaperTypesQuery;
use Illuminate\Database\Eloquent\Collection;

class GetPaperTypesHandler extends AbstractPaperTypeHandler
{
    public function handle(GetPaperTypesQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
