<?php

namespace App\CQRS\Queries\SourceProducts;

use App\DTOs\CollectionQueryDtoInterface;

interface SourceProductsCollectionQueryDtoInterface extends CollectionQueryDtoInterface
{
    public function getBrandCode(): ?string;
}
