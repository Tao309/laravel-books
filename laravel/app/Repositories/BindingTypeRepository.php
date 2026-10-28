<?php

namespace App\Repositories;

use App\CQRS\Queries\GetCollectionQueryInterface;
use App\Models\BindingType;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BindingTypeRepository implements RepositoryInterface
{
    public function create(array $data): BindingType
    {
        return BindingType::create($data);
    }

    public function get(int $id): BindingType
    {
        return BindingType::findOrFail($id);
    }

    public function getAll(GetCollectionQueryInterface $query): Collection
    {
        return BindingType::all();
    }

    public function getAllPaginate($query): LengthAwarePaginator
    {
        return BindingType::paginate();
    }

    public function update(int $id, array $data): BindingType
    {
        $entity = BindingType::findOrFail($id);
        $entity->update($data);

        return $entity;
    }

    public function delete($id): bool
    {
        $entity = BindingType::findOrFail($id);

        return $entity->delete();
    }
}
