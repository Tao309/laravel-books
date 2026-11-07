<?php

namespace App\Repositories\Interfaces;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\DTOs\DtoInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function create(DtoInterface $dto): Model;

    public function get(int $id): Model;

    public function getAll(GetCollectionQueryInterface $query): Collection;

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator;

    public function update(int $id, DtoInterface $dto): Model;

    public function delete($id): bool;
}
