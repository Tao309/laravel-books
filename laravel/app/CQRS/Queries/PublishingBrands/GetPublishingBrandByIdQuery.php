<?php

namespace App\CQRS\Queries\PublishingBrands;

use App\CQRS\Queries\GetByIdQueryInterface;

class GetPublishingBrandByIdQuery implements GetByIdQueryInterface
{
    public function __construct(public int $id) {}

}
