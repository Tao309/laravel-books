<?php

namespace App\CQRS\Queries\BindingTypes;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetBindingTypeByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
