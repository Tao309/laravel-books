<?php

namespace App\Queries\BindingTypes;

use App\Queries\GetByIdQueryInterface;

class GetBindingTypeByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
