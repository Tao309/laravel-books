<?php

namespace App\CQRS\Handlers\BindingTypes;

use App\CQRS\Queries\BindingTypes\GetBindingTypeByIdQuery;
use App\Models\BindingType;

class GetBindingTypeByIdHandler extends AbstractBindingTypeHandler
{
    public function handle(GetBindingTypeByIdQuery $query): BindingType
    {
        return $this->repository->get($query->id);
    }
}
