<?php

namespace App\Services\Interfaces;

use App\DTOs\DtoInterface;
use Illuminate\Database\Eloquent\Model;

interface ServiceInterface
{
    public function getById(int $id): ?Model;

    public function create(DtoInterface $dto): ?Model;

    public function updateById(int $id, DtoInterface $dto): ?Model;

    public function deleteById(int $id): bool;
}
