<?php

namespace App\CQRS\Commands\PublishingBrands;

use App\DTOs\DtoInterface;

class UpdatePublishingBrandCommand
{
    public function __construct(public int $id, public DtoInterface $dto) {}
}
