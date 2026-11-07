<?php

namespace App\DTOs;

interface UserDataDtoInterface
{
    public static function fromArray(array $data): self;

    public function getEntityData(): array;
}
