<?php

namespace App\Http\Requests;

use App\DTOs\CollectionQueryDtoInterface;

interface QueryCollectionRequestInterface
{
    public function messages(): array;

    public function toDTO(): CollectionQueryDtoInterface;
}
