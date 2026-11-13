<?php

namespace App\CQRS\Handlers\SourceProductTypes;

use App\CQRS\Queries\SourceProductTypes\GetSourceProductTypeByIdQuery;
use App\Models\SourceProductType;

class GetSourceProductByIdTypeHandler extends AbstractSourceProductTypeHandler
{
    public function handle(GetSourceProductTypeByIdQuery $query): SourceProductType
    {
        return $this->repository->get($query->id);
    }
}
