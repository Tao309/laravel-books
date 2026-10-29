<?php

namespace App\Http\Requests;

use App\DTOs\DtoInterface;

interface RequestInterface
{
    public function messages(): array;

    public function toDTO(): DtoInterface;
}
