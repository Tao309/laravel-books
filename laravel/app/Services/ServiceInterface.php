<?php

namespace App\Services;

use App\DTOs\DtoInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServiceInterface
{
    public function getListPaginate(): ?LengthAwarePaginator;

    public function getList(): ?Collection;

    public function getById(int $id): ?Model;

    public function create(DtoInterface $dto): ?Model;

    public function updateById(int $id, DtoInterface $dto): ?Model;
}
