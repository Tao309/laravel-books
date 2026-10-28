<?php

namespace App\CQRS\Commands\PublishingBrands;

use App\DTOs\DtoInterface;

class CreatePublishingBrandCommand
{
    public function __construct(public DtoInterface $dto) {}
}
