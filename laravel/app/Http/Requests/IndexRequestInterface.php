<?php

namespace App\Http\Requests;

use App\DTOs\CollectionQueryDtoInterface;

interface IndexRequestInterface
{
    public function messages(): array;

    public function toDTO(): CollectionQueryDtoInterface;
}
