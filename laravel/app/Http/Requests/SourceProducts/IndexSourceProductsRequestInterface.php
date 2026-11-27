<?php

namespace App\Http\Requests\SourceProducts;

use App\CQRS\Queries\SourceProducts\SourceProductsCollectionQueryDtoInterface;

interface IndexSourceProductsRequestInterface
{
    public function messages(): array;

    public function toDTO(): SourceProductsCollectionQueryDtoInterface;
}
