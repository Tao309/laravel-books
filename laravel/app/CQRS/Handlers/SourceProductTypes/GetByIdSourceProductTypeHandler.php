<?php

namespace App\CQRS\Handlers\SourceProductTypes;

use App\CQRS\Queries\SourceProductTypes\GetByIdSourceProductTypeQuery;
use App\Models\SourceProductType;

class GetByIdSourceProductTypeHandler extends AbstractSourceProductTypeHandler
{
    public function handle(GetByIdSourceProductTypeQuery $query): SourceProductType
    {
        return $this->repository->get($query->id);
    }
}
