<?php

namespace App\CQRS\Queries\SourceProducts;

interface GetSourceProductsCollectionQueryInterface
{
    public function getDTO(): SourceProductsCollectionQueryDtoInterface;
}
