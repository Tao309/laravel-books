<?php

namespace App\CQRS\Handlers\PaperTypes;

use App\CQRS\Queries\PaperTypes\GetByIdPaperTypeQuery;
use App\Models\PaperType;

class GetByIdPaperTypeHandler extends AbstractPaperTypeHandler
{
    public function handle(GetByIdPaperTypeQuery $query): PaperType
    {
        return $this->repository->get($query->id);
    }
}
