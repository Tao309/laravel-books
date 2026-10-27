<?php

namespace App\Repositories\Interfaces;

use App\Queries\GetByIdQueryInterface;
use App\Queries\GetCollectionQueryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function create(array $data): Model;

    public function get(GetByIdQueryInterface $id): Model;

    public function getAll(GetCollectionQueryInterface $query): Collection;

    public function getAllPaginate(GetCollectionQueryInterface $query): LengthAwarePaginator;

    public function update(int $id, array $data): Model;

    public function delete($id): bool;
}
