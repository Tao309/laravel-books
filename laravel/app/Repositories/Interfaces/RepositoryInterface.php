<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function create(array $data): Model;

    public function get($id): Model;

    public function getAllPaginate($query): LengthAwarePaginator;

    public function update(int $id, array $data): Model;

    public function delete($id): bool;
}
