<?php

namespace App\CQRS\Handlers\BindingTypes;

use App\CQRS\Queries\BindingTypes\GetBindingTypesQuery;
use Illuminate\Database\Eloquent\Collection;

class GetBindingTypesHandler extends AbstractBindingTypeHandler
{
    public function handle(GetBindingTypesQuery $query): Collection
    {
        return $this->repository->getAll($query);
    }
}
