<?php

namespace App\CQRS\Handlers\PaperTypes;

use App\CQRS\Queries\PaperTypes\GetPaperTypeByIdQuery;
use App\Models\PaperType;

class GetPaperTypeByIdHandler extends AbstractPaperTypeHandler
{
    public function handle(GetPaperTypeByIdQuery $query): PaperType
    {
        return $this->repository->get($query->id);
    }
}
